<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain;

use SOFe\AwaitGenerator\Await;
use SOFe\Zleep\Zleep;
use pocketmine\block\Block;
use pocketmine\block\GlassPane;
use pocketmine\block\StainedGlassPane;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\ItemBlock;
use pocketmine\item\VanillaItems;
use pocketmine\math\Axis;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\convert\BlockTranslator;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\ClientboundPacket;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\world\World;
use pocketmine\world\format\Chunk;

final class Main extends PluginBase implements Listener {
    protected function onEnable() : void {
        $pluginManager = $this->getServer()->getPluginManager();
        foreach ([
            Zleep::class,
        ] as $virion) {
            if (!class_exists($virion)) {
                $this->getLogger()->critical("Please re-download the plugin PHAR from https://poggit.pmmp.io/p/GlassPain");
                $pluginManager->disablePlugin($this);
                return;
            }
        }

        $pluginManager->registerEvents($this, $this);
        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask($this->validatePlayersPosTask(...)), 20);
        $this->blockTranslator = (new TypeConverter)->getBlockTranslator();
    }

    private function isTrackingPlayer(Player $player) : bool {
        return $player->hasPermission("glasspain.use");
    }

    private array $playerHelds = [];

    private array $playerSights = [];

    private BlockTranslator $blockTranslator;

    /**
     * @priority MONITOR
     */
    public function onPlayerItemHeld(PlayerItemHeldEvent $event) : void {
        Await::f2c(function () use ($event) : \Generator {
            $player = $event->getPlayer();
            $this->playerHelds[$player->getId()] = $event;
            yield from Zleep::sleepTicks($this, 5);
            if (($this->playerHelds[$player->getId()] ?? null) !== $event) return;
            unset($this->playerHelds[$player->getId()]);

            $item = $event->getItem();
            $this->updateSight(
                $player,
                createSight: $this->isTrackingPlayer($player) && $item instanceof ItemBlock && !$item->equals(VanillaItems::AIR())
            );
            
        });
    }

    private function updateSight(Player $player, bool $createSight) : void {
        $newSight = [];
        if ($createSight) {
            $playerPos = $player->getPosition();
            $dynPlayerPos = [$playerPos->getX(), $playerPos->getY(), $playerPos->getZ()];
            $box = new AxisAlignedBB(...[...$dynPlayerPos, ...$dynPlayerPos]);
            static $maxReach = 13; // Player::MAX_REACH_DISTANCE_CREATIVE
            static $lessReach = 6;
            $facing = $player->getHorizontalFacing();
            $box->extend($facing, $maxReach);
            $box->stretch(Axis::Y, $lessReach);
            $box->stretch(Facing::axis(Facing::rotateY($facing, clockwise: true)), $lessReach);
            
            for ($X = (int)$box->minX; $X <= $box->maxX; $X++) {
                for ($Y = (int)$box->minY; $Y <= $box->maxY; $Y++) {
                    for ($Z = (int)$box->minZ; $Z <= $box->maxZ; $Z++) {
                        $newSight[World::blockHash($X, $Y, $Z)] = new Vector3($X, $Y, $Z);
                    }
                }
            }
        }
        $oldSight = $this->playerSights[$player->getId()] ?? [];
        $this->playerSights[$player->getId()] = $newSight;
        $newHashes = array_keys($newSight);
        $oldHashes = array_keys($oldSight);

        $world = $player->getWorld();
        $packets = [];
        foreach (array_diff($oldHashes, $newHashes) as $hash) {
            $pos = $oldSight[$hash];
            $chunk = $world->getChunk(
                $pos->getFloorX() >> Chunk::COORD_BIT_SIZE,
                $pos->getFloorZ() >> Chunk::COORD_BIT_SIZE,
            );
            $packets[] = UpdateBlockPacket::create(
                BlockPosition::fromVector3($pos),
                $this->blockTranslator->internalIdToNetworkId($chunk->getBlockStateId(
                    $pos->getFloorX() & Chunk::COORD_MASK,
                    $pos->getFloorY(),
                    $pos->getFloorZ() & Chunk::COORD_MASK,
                )),
                UpdateBlockPacket::FLAG_NETWORK,
                UpdateBlockPacket::DATA_LAYER_NORMAL,
            );
        }

        foreach (array_diff($newHashes, $oldHashes) as $hash) {
            $pos = $newSight[$hash];
            $block = $world->getBlock($pos);
            $thick = $this->blockThinToThick($block);
            if ($thick === null) continue;
            $packets[] = $this->createFakeBlockPacket($pos, $thick);
        }
        $this->dispatchPackets($player, $packets);
    }

    private function createFakeBlockPacket(Vector3 $pos, Block $block) : ClientboundPacket {
        static $networkIdCache = [];
        return UpdateBlockPacket::create(
            BlockPosition::fromVector3($pos),
            $networkIdCache[spl_object_id($block)] ??= $this->blockTranslator->internalIdToNetworkId($block->getStateId()),
            UpdateBlockPacket::FLAG_NETWORK,
            UpdateBlockPacket::DATA_LAYER_NORMAL,
        );
    }

    /**
     * @param ClientboundPacket[] $packets
     */
    private function dispatchPackets(Player $player, array $packets) : void {
        $connection = $player->getNetworkSession();
        $sendIndex = count($packets) - 1;
        foreach ($packets as $index => $packet) $connection->sendDataPacket($packet, $index === $sendIndex);
    }

    private function blockThinToThick(Block $block) : ?Block {
        if (!$block instanceof GlassPane) return null;
        if (!$block instanceof StainedGlassPane) return VanillaBlocks::GLASS();
        return VanillaBlocks::STAINED_GLASS()->setColor($block->getColor());
    }

    /**
     * @priority MONITOR
     */
    public function onBlockPlace(BlockPlaceEvent $event) : void {
        Await::f2c(function () use ($event) : \Generator {
            $player = $event->getPlayer();
            if (!$this->isTrackingPlayer($player)) return;
            $shouldUpdate = false;
            $hashes = [];
            foreach ($event->getTransaction()->getBlocks() as [$X, $Y, $Z, $block]) {
                if (!$shouldUpdate && $this->blockThinToThick($block) !== null) $shouldUpdate = true;
                $hashes[] = World::blockHash($X, $Y, $Z);
            }
            if ($shouldUpdate) {
                yield from Zleep::sleepTicks($this, 5);
                $sight = $this->playerSights[$player->getId()] ?? null;
                if ($sight !== null) {
                    foreach ($hashes as $hash) unset($sight[$hash]);
                    $this->playerSights[$player->getId()] = $sight;
                }
                $this->updateSight($player, createSight: true);
            }
        });
    }

    /**
     * @priority MONITOR
     */
    public function onEntityTeleport(EntityTeleportEvent $event) : void {
        $player = $event->getEntity();
        if (!$player instanceof Player) return;
        unset($this->playerSights[$player->getId()]);
    }

    /**
     * @priority MONITOR
     */
    public function onPlayerQuit(PlayerQuitEvent $event) : void {
        unset($this->playerSights[$event->getPlayer()->getId()]);
    }

    private function validatePlayersPosTask() : void {
        foreach ($this->getServer()->getOnlinePlayers() as $player) {
            $sight = $this->playerSights[$player->getId()] ?? [];
            if ($sight === []) continue;
            // TODO
        }
    }
}

class Messages {
    public function __construct(
    ) {
    }
}
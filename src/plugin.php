<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain;

use SOFe\AwaitGenerator\Await;
use SOFe\Zleep\Zleep;
use pocketmine\block\Block;
use pocketmine\block\GlassPane;
use pocketmine\block\HardenedGlassPane;
use pocketmine\block\StainedGlassPane;
use pocketmine\block\StainedHardenedGlassPane;
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
use pocketmine\utils\TextFormat;
use pocketmine\world\World;
use pocketmine\world\format\Chunk;

final class Main extends API implements Listener {
    protected function onLoad() : void {
        InternalStub::$instance = $this;
    }

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
        $this->blockTranslator = (new TypeConverter)->getBlockTranslator();

        foreach ($this->getResources() as $path => $file) {
            if (!str_starts_with($path, "translations/")) continue;
            $fileObject = $file->openFile();
            $raw = $fileObject->fread($file->getSize());
            unset($fileObject);

            $entries = yaml_parse($raw);
            foreach ($entries["codes"] as $code) {
                $this->messages[$code] = new Messages(
                    blocksReplaced: TextFormat::colorize($entries["blocks-replaced"]),
                    tooClose: TextFormat::colorize($entries["too-close"]),
                );
            }
        }
    }

    private function isTrackingPlayer(Player $player) : bool {
        return $player->hasPermission("glasspain.use");
    }

    /**
     * @var array<int, PlayerItemHeldEvent> Key = the player entity runtime ID.
     */
    private array $playerHelds = [];

    private BlockTranslator $blockTranslator;

    /**
     * @priority MONITOR
     */
    public function onPlayerItemHeld(PlayerItemHeldEvent $event) : void {
        Await::f2c(function () use ($event) : \Generator {
            $player = $event->getPlayer();
            $this->playerHelds[$player->getId()] = $event;
            yield from Zleep::sleepTicks($this, 5);
            if (!$player->isConnected()) return;
            if (($this->playerHelds[$player->getId()] ?? null) !== $event) return;
            unset($this->playerHelds[$player->getId()]);
            $this->lazyPlayerItemHeld($event);
        });
    }

    private function lazyPlayerItemHeld(PlayerItemHeldEvent $event) : void {
        $item = $event->getItem();
        $player = $event->getPlayer();
        $createSight = $this->isTrackingPlayer($player) && $item instanceof ItemBlock && !$item->equals(VanillaItems::AIR());
        $this->updateSight(
            $player,
            createSight: $createSight,
        );
    }

    /**
     * @var array<int, Player> Key = the player entity runtime ID.
     */
    private array $playerSightLocks = [];

    private function updateSight(Player $player, bool $createSight) : void {
        $world = $player->getWorld();
        $checksPos = [
            $playerPos = $player->getPosition(),
            $playerPos->up(),
        ];
        if (!$player->isFlying()) $checksPos[] = $playerPos->down();
        foreach ($checksPos as $checkPos) {
            if ($this->blockThinToThick($world->getBlock($checkPos)) !== null) {
                if (isset($this->playerSightLocks[$player->getId()])) return;
                $this->playerSightLocks[$player->getId()] = $player;
                Await::f2c(function () use ($player) : \Generator {
                    $show = true;
                    $messages = $this->getMessages($player);
                    while ($player->isConnected() && isset($this->playerSightLocks[$player->getId()])) {
                        $player->sendTitle(
                            title: TextFormat::RESET,
                            subtitle: $show ? $messages->tooClose : "",
                            fadeIn: 0,
                            stay: 40,
                            fadeOut: 0,
                        );
                        yield from Zleep::sleepTicks($this, 20);
                        $show = !$show;
                    }
                });

                return; // Player's position is unsafe to updating sight.
            }
        }

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

        $packets = $updatesPos = [];
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
            $updatesPos[] = $pos;
        }

        $sent = false;
        foreach (array_diff($newHashes, $oldHashes) as $hash) {
            $pos = $newSight[$hash];
            $block = $world->getBlock($pos);
            $thick = $this->blockThinToThick($block);
            if ($thick === null) continue;
            if (!$sent) {
                $messages = $this->getMessages($player);
                $player->sendPopup($messages->blocksReplaced);
                $sent = true;
            }
            $packets[] = $this->createFakeBlockPacket($pos, $thick);
            $updatesPos[] = $pos;
        }

        $this->dispatchPackets($player, $packets);
        unset($this->playerSightLocks[$player->getId()]);
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

    protected function blockThinToThick(Block $block) : ?Block {
        if ($block instanceof GlassPane) return VanillaBlocks::GLASS();
        if ($block instanceof HardenedGlassPane) return VanillaBlocks::HARDENED_GLASS();
        if ($block instanceof StainedGlassPane) return VanillaBlocks::STAINED_GLASS()->setColor($block->getColor());
        if ($block instanceof StainedHardenedGlassPane) return VanillaBlocks::STAINED_HARDENED_GLASS()->setColor($block->getColor());
        return null;
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
                if (!$player->isConnected()) return;
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
        unset($this->playerSightLocks[$event->getPlayer()->getId()]);
    }

    private function getMessages(Player $player) : Messages {
        return $this->messages[$player->getLocale()] ?? $this->messages["en_GB"];
    }

    /**
     * @var array<string, Messages> Key = locale code.
     */
    private array $messages = [];
}

class Messages {
    public function __construct(
        public readonly string $blocksReplaced,
        public readonly string $tooClose,
    ) {
    }
}
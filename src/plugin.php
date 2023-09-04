<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain;

use pocketmine\block\Block;
use pocketmine\block\GlassPane;
use pocketmine\block\StainedGlassPane;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\item\ItemBlock;
use pocketmine\math\Axis;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\convert\TypeConverter;
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
        // foreach ([
        //     Zleep::class,
        // ] as $virion) {
        //     if (!class_exists($virion)) {
        //         $this->getLogger()->critical("Please re-download the plugin PHAR from https://poggit.pmmp.io/p/GlassPain");
        //         $pluginManager->disablePlugin($this);
        //         return;
        //     }
        // }

        $pluginManager->registerEvents($this, $this);
        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask($this->validatePlayersPosTask(...)), 20);
    }

    private function isTrackingPlayer(Player $player) : bool {
        return $player->hasPermission("glasspain.use");
    }

    private array $playerSights = [];

    public function onPlayerItemHeld(PlayerItemHeldEvent $event) : void {
        $player = $event->getPlayer();
        if (!$this->isTrackingPlayer($player)) return;
        $item = $event->getItem();
        if (!$event instanceof ItemBlock) return;
        $playerPos = $player->getPosition();
        $dynPlayerPos = [$playerPos->getX(), $playerPos->getY(), $playerPos->getZ()];

        $newSight = [];
        $thick = $this->blockThinToThick($item->getBlock(clickedFace: null));
        if ($thick !== null) {
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
        $translator = (new TypeConverter)->getBlockTranslator();
        $packets = [];
        foreach (array_diff($oldHashes, $newHashes) as $hash) {
            $pos = $oldSight[$hash];
            $chunk = $world->getChunk(
                $pos->getFloorX() >> Chunk::COORD_BIT_SIZE,
                $pos->getFloorZ() >> Chunk::COORD_BIT_SIZE,
            );
            $packets[] = UpdateBlockPacket::create(
                BlockPosition::fromVector3($pos),
                $translator->internalIdToNetworkId($chunk->getBlockStateId(
                    $pos->getFloorX() & Chunk::COORD_MASK,
                    $pos->getFloorY(),
                    $pos->getFloorZ() & Chunk::COORD_MASK,
                )),
                UpdateBlockPacket::FLAG_NETWORK,
                UpdateBlockPacket::DATA_LAYER_NORMAL,
            );
        }

        static $stateIdCache = [];
        $getNetworkId = static function ($block) use ($translator, &$stateIdCache) {
            return $stateIdCache[spl_object_id($block)] ?? $translator->internalIdToNetworkId($block->getStateId());
        };
        foreach (array_diff($newHashes, $oldHashes) as $hash) {
            $pos = $newSight[$hash];
            $block = $world->getBlock($pos);
            $thick = $this->blockThinToThick($block);
            if ($thick === null) continue;
            $packets[] = UpdateBlockPacket::create(
                BlockPosition::fromVector3($pos),
                $getNetworkId($thick),
                UpdateBlockPacket::FLAG_NETWORK,
                UpdateBlockPacket::DATA_LAYER_NORMAL,
            );
        }

        $connection = $player->getNetworkSession();
        $immediateIndex = count($packets) - 1;
        foreach ($packets as $index => $packet) $connection->sendDataPacket($packet, $index === $immediateIndex);
    }

    private function blockThinToThick(Block $block) : ?Block {
        if (!$block instanceof GlassPane) return null;
        if (!$block instanceof StainedGlassPane) return VanillaBlocks::GLASS();
        return VanillaBlocks::STAINED_GLASS()->setColor($block->getColor());
    }

    public function onBlockPlace(BlockPlaceEvent $event) : void {
        // TODO
    }

    public function onEntityTeleport(EntityTeleportEvent $event) : void {
        $player = $event->getEntity();
        if (!$player instanceof Player) return;
        unset($this->playerSights[$player->getId()]);
    }

    private function validatePlayersPosTask() : void {
        foreach ($this->getServer()->getOnlinePlayers() as $player) {
            $sight = $this->playerSights[$player->getId()] ?? [];
            if ($sight === []) continue;
            // TODO
        }
    }
}

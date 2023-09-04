<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain;

use pocketmine\block\Block;
use pocketmine\block\GlassPane;
use pocketmine\block\StainedGlassPane;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\math\VoxelRayTrace;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\world\World;
use pocketmine\world\format\Chunk;

final class Main extends PluginBase implements Listener {
    protected function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    private function isTrackingPlayer(Player $player) : bool {
        return $player->hasPermission("glasspain.use");
    }

    private array $playerSights = [];

    public function onPlayerMove(PlayerMoveEvent $event) : void {
        $player = $event->getPlayer();
        if (!$this->isTrackingPlayer($player)) return;
        $newSight = [];
        foreach (VoxelRayTrace::inDirection(
            $player->getPosition()->add(0, $player->getEyeHeight(), 0),
            $player->getDirectionVector(),
            13, // $player::MAX_REACH_DISTANCE_CREATIVE
        ) as $pos) $newSight[World::blockHash(
            $pos->getFloorX(),
            $pos->getFloorY(),
            $pos->getFloorZ(),
        )] = $pos;
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

    public function onEntityTeleport(EntityTeleportEvent $event) : void {
        $player = $event->getEntity();
        if (!$player instanceof Player) return;
        unset($this->playerSights[$player->getId()]);
    }
}

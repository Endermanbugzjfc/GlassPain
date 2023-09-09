<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain;

use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\world\World;

abstract class API extends InternalStub {
    protected static self $instance;
    public static function getInstance() : self {
        return self::$instance;
    }

    /**
     * For anticheats.
     */
    public function getClientSideBlock(Player $player, Vector3 $pos) : Block {
        $hash = World::blockHash(
            $pos->getFloorX(),
            $pos->getFloorY(),
            $pos->getFloorZ(),
        );
        $block = $player->getWorld()->getBlock($pos);
        if (!isset($this->playerSights[$player->getId()][$hash])) {
            return $block;
        }
        return $this->blockThinToThick($block) ?? $block;
    }
}
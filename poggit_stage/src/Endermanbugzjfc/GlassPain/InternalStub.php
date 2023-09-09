<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain;

use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\world\World;
























abstract class InternalStub extends PluginBase {
    /**
     * @var array<int, array<int, Vector3>> First key = the player entity runtime ID. Second key = the block hash.
     */
    protected array $playerSights = [];
    abstract protected function blockThinToThick(Block $block) : ?Block;
}
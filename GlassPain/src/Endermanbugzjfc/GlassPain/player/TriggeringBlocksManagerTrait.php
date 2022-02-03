<?php

namespace Endermanbugzjfc\GlassPain\player;

use pocketmine\block\Block;

trait TriggeringBlocksManagerTrait
{

    /**
     * @var Block[]
     */
    protected array $triggeringBlocks;

    /**
     * @return Block[]
     */
    public function getTriggeringBlocks() : array
    {
        return $this->triggeringBlocks;
        // TODO: Get default triggering blocks
    }

    public function isTriggeringBlock(
        Block $getBlock
    ) : bool
    {
    }

}
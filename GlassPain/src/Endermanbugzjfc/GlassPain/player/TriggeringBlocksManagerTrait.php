<?php

namespace Endermanbugzjfc\GlassPain\player;

use pocketmine\item\ItemBlock;

trait TriggeringBlocksManagerTrait
{

    /**
     * @var ItemBlock[]
     */
    protected array $triggeringBlocks;

    /**
     * @return ItemBlock[]
     */
    public function getTriggeringBlocks() : array
    {
        return $this->triggeringBlocks;
        // TODO: Get default triggering blocks
    }

    public function isTriggeringBlock(
        ItemBlock $block
    ) : bool
    {

    }

}
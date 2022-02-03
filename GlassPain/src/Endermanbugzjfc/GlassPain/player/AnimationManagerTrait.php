<?php

namespace Endermanbugzjfc\GlassPain\player;

use Endermanbugzjfc\GlassPain\animation\AnimationConfig;
use Endermanbugzjfc\GlassPain\GlassPain;
use pocketmine\block\Block;
use pocketmine\item\ItemBlock;

trait AnimationManagerTrait
{

    /**
     * @return AnimationConfig[]
     */
    public function getAvailableAnimations() : array
    {

    }

    /**
     * @return AnimationConfig[] With modified options, ordered by last enable time from old to recent.
     */
    public function getAnimations() : array
    {
    }

    /**
     * @var ItemBlock[]
     */
    protected array $triggeringBlocks;

    /**
     * @return ItemBlock[]
     */
    public function getTriggeringBlocks() : array
    {
        return $this->triggeringBlocks ?? (GlassPain
                    ::getInstance()
                    ->config
                    ->defaultAnimation
                    ?->parseTriggeringBlockIds()
                ?? []
            );
    }

    public function isTriggeringBlock(
        Block $block
    ) : bool
    {
        foreach ($this->getTriggeringBlocks() as $sBlock) {
            if ($block->asItem()->equals(
                $sBlock,
                true,
                false
            )) {
                return true;
            }
        }
        return false;
    }

}
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
     * @return AnimationConfig[] The {@link AnimationConfig} is a clone of the global one and hold options which may be modified by the player. Ordered by last enable time from old to recent.
     */
    public function getAnimations() : array
    {
    }

    /**
     * @return AnimationConfig|null The {@link AnimationConfig} is a clone of the global one and hold options which may be modified by the player.
     */
    public function getAnimation() : ?AnimationConfig {

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
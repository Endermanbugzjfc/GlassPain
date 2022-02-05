<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\player;

use Endermanbugzjfc\GlassPain\animation\AnimationBase;
use Endermanbugzjfc\GlassPain\GlassPain;
use pocketmine\block\Block;
use pocketmine\item\ItemBlock;

trait AnimationUserTrait
{

    /**
     * @return AnimationBase[]
     */
    public function getAvailableAnimations() : array
    {

    }

    /**
     * @return AnimationBase[]
     */
    public function getAnimations() : array
    {
    }

    public function getAnimation() : ?AnimationBase
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
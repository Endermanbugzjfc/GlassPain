<?php

namespace Endermanbugzjfc\GlassPain\config;

use Endermanbugzjfc\ConfigStruct\KeyName;
use pocketmine\block\utils\DyeColor;

class GlassPainConfig
{

    #[KeyName("default-animation")]
    public ?string $defaultAnimation = null;

    #[KeyName("default-triggering-blocks")]
    public array $defaultTriggeringBlocks;

    public function __construct()
    {
        foreach ([
                     "glass_pane",
                     "hard_glass_pane",
                     "stained_glass_pane",
                     "stained_hardened_glass_pane"
                 ] as $block
        ) {
            foreach (DyeColor::getAll() as $colour) {
                $blocks[] = $colour->name() . "_" . $block;
            }
        }
        $blocks[] = "iron_bars";
        $this->defaultTriggeringBlocks = $blocks;
    }

}
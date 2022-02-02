<?php

namespace Endermanbugzjfc\GlassPain\config;

use Endermanbugzjfc\ConfigStruct\KeyName;

class GlassPainConfig
{

    #[KeyName("fallback-animation")]
    public ?string $fallbackAnimation = null;

    #[KeyName("default-triggering-blocks")]
    public array $defaultTriggeringBlocks = [
        "glass_pane",
        "hard_glass_pane",
        "stained_glass_pane",
        "stained_hardened_glass_pane",
        "iron_bars"
    ];

}
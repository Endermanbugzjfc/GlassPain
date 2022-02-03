<?php

namespace Endermanbugzjfc\GlassPain\animation;

use Endermanbugzjfc\GlassPain\Utils;
use pocketmine\block\utils\DyeColor;

class AnimationConfig
{

    public string $class = "please refer to the information provided by your animation plugin";

    public string $permission = Utils::LOWERCASE_PLUGIN_NAME . ".animation.";

    public array $optionPermissions = [
        "option" => Utils::LOWERCASE_PLUGIN_NAME . ".animation-option.",
        "option-without-permission" => null
    ];

    public array $defaultOptionValues = [
    ];

    public array $triggeringBlocks = [
    ];

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
        $this->triggeringBlocks = $blocks;
    }

}
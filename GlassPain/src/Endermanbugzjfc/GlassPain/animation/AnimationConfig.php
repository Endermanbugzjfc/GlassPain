<?php

namespace Endermanbugzjfc\GlassPain\animation;

use Endermanbugzjfc\ConfigStruct\KeyName;
use Endermanbugzjfc\GlassPain\Utils;
use pocketmine\block\utils\DyeColor;

class AnimationConfig
{

    public string $class = "please refer to the information provided by your animation plugin";

    public string $permission = Utils::LOWERCASE_PLUGIN_NAME . ".animation.";

    #[KeyName("option-permission")]
    public array $optionPermissions = [
        "option" => Utils::LOWERCASE_PLUGIN_NAME . ".animation-option.",
        "option-without-permission" => null
    ];

    #[KeyName("default-option-values")]
    public array $defaultOptionValues = [
    ];

    #[KeyName("triggering-blocks")]
    public array $triggeringBlocks = [
    ];

    #[KeyName("edit-triggering-block-permission")]
    public ?string $editTriggeringBlockPermission = Utils::LOWERCASE_PLUGIN_NAME . ".animation.";

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
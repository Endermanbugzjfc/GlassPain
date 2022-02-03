<?php

namespace Endermanbugzjfc\GlassPain\animation;

use Endermanbugzjfc\GlassPain\Utils;

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
}
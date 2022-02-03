<?php

namespace Endermanbugzjfc\GlassPain\animation;

use Endermanbugzjfc\GlassPain\GlassPainUtils;

class AnimationConfig
{

    public string $class = "please refer to the information provided by your animation plugin";

    public string $permission = GlassPainUtils::LOWERCASE_PLUGIN_NAME . ".animation.";

    public array $optionPermissions = [
        "option" => GlassPainUtils::LOWERCASE_PLUGIN_NAME . ".animation-option.",
        "option-without-permission" => null
    ];

    public array $defaultOptionValues = [
    ];
}
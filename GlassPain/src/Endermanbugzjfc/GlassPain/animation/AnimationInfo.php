<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\animation;

use SOFe\InfoAPI\Info;

final class AnimationInfo extends Info
{
    public function __construct(
        protected AnimationConfig $value
    )
    {
    }

    public function toString() : string
    {
    }

    public static function init() : void
    {

    }

    /**
     * @return AnimationConfig
     */
    public function getValue() : AnimationConfig
    {
        return $this->value;
    }

}

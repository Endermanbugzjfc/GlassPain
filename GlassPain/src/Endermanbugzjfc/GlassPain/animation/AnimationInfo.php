<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\animation;

use SOFe\InfoAPI\Info;

final class AnimationInfo extends Info
{
    public function __construct(
        protected AnimationConfig $animation
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
    public function getAnimation() : AnimationConfig
    {
        return $this->animation;
    }

}

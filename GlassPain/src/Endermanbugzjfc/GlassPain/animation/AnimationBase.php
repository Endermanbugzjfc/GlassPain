<?php

namespace Endermanbugzjfc\GlassPain\animation;

use Endermanbugzjfc\GlassPain\player\PlayerSession;

abstract class AnimationBase
{

    abstract public static function load(
        ?array $options
    ) : self;

    abstract public function getPlayDuration() : float;

}
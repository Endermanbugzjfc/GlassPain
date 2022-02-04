<?php

namespace Endermanbugzjfc\GlassPain\animation;

use Endermanbugzjfc\GlassPain\player\PlayerSession;

interface AnimationInterface
{

    public static function load(
        ?array $options
    ) : self;

    public function getPlayDuration() : float;

}
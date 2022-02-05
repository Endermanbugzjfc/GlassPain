<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\animation\presets\instant;

use Endermanbugzjfc\GlassPain\animation\AnimationBase;

class InstantAnimation extends AnimationBase
{

    protected function getMinimumPlayDuration() : float
    {
        return 0;
    }

    protected function getDefaultPlayDuration() : float
    {
        return 0;
    }

}
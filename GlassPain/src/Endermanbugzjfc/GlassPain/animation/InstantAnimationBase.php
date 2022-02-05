<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\animation;

abstract class InstantAnimationBase extends AnimationBase
{

    final protected function getMinimumPlayDuration() : float {
        return 0;
    }

    final protected function getDefaultPlayDuration() : float {
        return 0;
    }

}
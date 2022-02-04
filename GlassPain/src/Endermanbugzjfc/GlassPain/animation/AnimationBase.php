<?php

namespace Endermanbugzjfc\GlassPain\animation;

abstract class AnimationBase
{

    protected array $options = [];

    public function getDefaultOptions() : array
    {
        if ($this->getDefaultPlayDuration() > 0.0) {
            $return["speed"] = 1.0;
        }        return $return ?? [];
    }

    abstract protected function getMinimumPlayDuration() : float;

    abstract protected function getDefaultPlayDuration() : float;

}
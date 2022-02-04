<?php

namespace Endermanbugzjfc\GlassPain\animation;

abstract class AnimationBase
{

    protected array $options = [];

    public function getDefaultOptions() : array
    {
        return [
            "speed" => $this->getDefaultSpeedMultiplier()
        ];
    }

    abstract protected function getMinimumSpeedMultiplier() : float;

    abstract protected function getDefaultSpeedMultiplier() : float;

}
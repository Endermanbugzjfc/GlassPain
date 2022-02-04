<?php

namespace Endermanbugzjfc\GlassPain\animation;

abstract class AnimationBase
{

    protected array $options = [];

    public function getDefaultOptions() : array
    {
        if (!$this->isInstantAnimation()) {
            $return["speed"] = 1.0;
        }
        return $return ?? [];
    }

    private function isInstantAnimation() : bool
    {
        return $this->getMinimumPlayDuration() <= 0;
    }

    abstract protected function getMinimumPlayDuration() : float;

    abstract protected function getDefaultPlayDuration() : float;

}
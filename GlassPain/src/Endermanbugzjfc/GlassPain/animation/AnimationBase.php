<?php

namespace Endermanbugzjfc\GlassPain\animation;

abstract class AnimationBase
{

    protected array $options = [];

    final public function getDefaultOptions() : array
    {
        if (!$this instanceof InstantAnimation) {
            $return["speed"] = 1.0;
        }
        return $return ?? [];
    }
    
    public function getConfig() : AnimationConfig {
    }

    public function getInfo() : AnimationInfo {

    }

    abstract protected function getMinimumPlayDuration() : float;

    abstract protected function getDefaultPlayDuration() : float;

}
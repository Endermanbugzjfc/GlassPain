<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\animation;

abstract class AnimationBase
{

    protected ?array $options = null;

    final public function getOptions() : array {
        if ($this->options !== null) {
            return $this->options;
        }

        if (!$this instanceof InstantAnimationBase) {
            $return["speed"] = 1.0;
        }
        return $return ?? [];
    }

    final public function getUsersCount() : int {
    }

    public function getConfig() : AnimationConfig
    {
    }

    public function getInfo() : AnimationInfo
    {

    }

    abstract protected function getMinimumPlayDuration() : float;

    abstract protected function getDefaultPlayDuration() : float;

}
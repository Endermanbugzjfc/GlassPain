<?php

namespace Endermanbugzjfc\GlassPain;

use pocketmine\plugin\PluginBase;

class GlassPain extends PluginBase
{

    protected function onEnable() : void
    {
    }

    protected function onLoad() : void
    {
        self::$instance = $this;
    }

    protected static self $instance;

    public static function getInstance() : self {
        return self::$instance;
    }

}
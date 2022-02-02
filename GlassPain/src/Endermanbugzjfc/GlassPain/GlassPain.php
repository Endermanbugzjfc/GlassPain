<?php

namespace Endermanbugzjfc\GlassPain;

use pocketmine\plugin\PluginBase;
use SOFe\AwaitStd\AwaitStd;

class GlassPain extends PluginBase
{

    protected AwaitStd $std;

    protected function onEnable() : void
    {
        $this->getServer()->getPluginManager()->registerEvents(
            new EventListener(),
            $this
        );
        $this->std = AwaitStd::init($this);
    }

    /**
     * @return AwaitStd
     */
    public function getStd() : AwaitStd
    {
        return $this->std;
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
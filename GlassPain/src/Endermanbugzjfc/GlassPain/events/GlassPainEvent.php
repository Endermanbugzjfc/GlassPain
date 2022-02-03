<?php

namespace Endermanbugzjfc\GlassPain\events;

use Endermanbugzjfc\GlassPain\GlassPain;
use pocketmine\event\Event;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\plugin\Plugin;

abstract class GlassPainEvent extends PluginEvent
{

    public function __construct()
    {
        parent::__construct(GlassPain::getInstance());
    }

}
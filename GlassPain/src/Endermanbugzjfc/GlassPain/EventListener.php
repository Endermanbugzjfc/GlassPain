<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain;

use Endermanbugzjfc\GlassPain\player\PlayerSession;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;

class EventListener implements Listener
{

    public function __construct()
    {
    }

    public function onPlayerLoginEvent(PlayerLoginEvent $event) : void
    {
        PlayerSession::open($event->getPlayer());
    }

}
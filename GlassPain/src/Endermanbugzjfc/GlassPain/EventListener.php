<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain;

use Closure;
use Endermanbugzjfc\GlassPain\player\PlayerSession;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDataSaveEvent;
use pocketmine\event\player\PlayerLoginEvent;

class EventListener implements Listener
{

    public function __construct(
        protected Closure $onNewPlayer
    )
    {
    }

    public function onPlayerDataSaveEvent(PlayerDataSaveEvent $event) : void
    {
        if ($event->getPlayer()->hasPlayedBefore()) {
            return;
        }
        ($this->onNewPlayer)();
    }

    public function onPlayerLoginEvent(PlayerLoginEvent $event) : void
    {
        PlayerSession::open($event->getPlayer());
    }

}
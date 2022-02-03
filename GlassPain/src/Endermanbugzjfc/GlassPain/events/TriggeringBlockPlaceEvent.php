<?php

namespace Endermanbugzjfc\GlassPain\events;

use Endermanbugzjfc\GlassPain\player\PlayerSession;
use pocketmine\event\block\BlockPlaceEvent;

class TriggeringBlockPlaceEvent extends GlassPainEvent
{

    public function __construct(
        protected BlockPlaceEvent $blockPlaceEvent,
        protected PlayerSession   $playerSession
    )
    {
        parent::__construct();
    }

    /**
     * @return PlayerSession
     */
    public function getPlayerSession() : PlayerSession
    {
        return $this->playerSession;
    }

    /**
     * @return BlockPlaceEvent
     */
    public function getBlockPlaceEvent() : BlockPlaceEvent
    {
        return $this->blockPlaceEvent;
    }

}
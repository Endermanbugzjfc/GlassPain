<?php

namespace Endermanbugzjfc\GlassPain\events;

use Endermanbugzjfc\GlassPain\player\PlayerSession;

class PlayerSessionInitializationCompleteEvent extends GlassPainEvent
{

    public function __construct(
        protected PlayerSession $playerSession
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

}
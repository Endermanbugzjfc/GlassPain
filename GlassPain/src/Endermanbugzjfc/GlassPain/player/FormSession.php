<?php

namespace Endermanbugzjfc\GlassPain\player;

class FormSession
{

    public function __construct(
        protected PlayerSession $playerSession
    )
    {
        $this->overviewForm();
    }

    protected function overviewForm() : void
    {
    }

    /**
     * @return PlayerSession
     */
    public function getPlayerSession() : PlayerSession
    {
        return $this->playerSession;
    }

}
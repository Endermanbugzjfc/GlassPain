<?php

namespace Endermanbugzjfc\GlassPain\player;

class FormSession
{

    public function __construct(
        protected PlayerSession $playerSession
    )
    {
        $this->controlPanelForm();
    }

    protected function controlPanelForm() : void
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
<?php

namespace Endermanbugzjfc\GlassPain\animation;

use Endermanbugzjfc\GlassPain\player\PlayerSession;

interface AnimationInterface
{

    public function canUse(PlayerSession $playerSession) : bool;

    public function getIdentifier() : string;

}
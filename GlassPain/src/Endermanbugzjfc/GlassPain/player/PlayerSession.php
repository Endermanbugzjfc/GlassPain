<?php

namespace Endermanbugzjfc\GlassPain\player;

use pocketmine\player\Player;

final class PlayerSession
{

    public function __construct(
        protected Player $player
    )
    {
    }

    /**
     * @return Player
     */
    public function getPlayer() : Player
    {
        return $this->player;
    }

}
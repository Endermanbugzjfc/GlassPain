<?php

namespace Endermanbugzjfc\GlassPain\player;

use pocketmine\player\Player;

class PlayerSession
{

    public function __construct(
        protected Player $player
    )
    {
    }

    public static function open(Player $player) : self
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
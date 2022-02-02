<?php

namespace Endermanbugzjfc\GlassPain\player;

use pocketmine\player\Player;
use function spl_object_id;

class PlayerSession
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

    /**
     * @var self[]
     */
    protected static array $sessions = [];

    public static function open(Player $player) : self
    {
        return self::$sessions[spl_object_id($player)] = new self(
            $player
        );
    }

    public static function get(Player $player) : self
    {
        return self::$sessions[spl_object_id($player)]
            ??= self::open($player);
    }

}
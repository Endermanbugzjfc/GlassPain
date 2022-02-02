<?php

namespace Endermanbugzjfc\GlassPain\player;

use Endermanbugzjfc\GlassPain\GlassPain;
use pocketmine\player\Player;
use pocketmine\Server;
use SOFe\AwaitStd\Await;
use SOFe\AwaitStd\AwaitStd;
use SOFe\AwaitStd\DisposeException;
use function array_diff;
use function array_keys;
use function spl_object_id;

class PlayerSession
{

    public function __construct(
        protected Player $player
    )
    {
        Await::f2c(function () {
            try {
                $std = GlassPain::getInstance()->getStd();
                while (true) {
                    $this->coroutine($std);
                }
            } catch (DisposeException) {
                $this->close();
            }
        });
    }

    public function coroutine(
        AwaitStd $std
    ) : void
    {
    }

    protected function close(
        bool $clean = false
    ) : void
    {
        unset(self::$sessions[spl_object_id($this->getPlayer())]);
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

    public static function open(
        Player $player,
        bool   $clean = false
    ) : self
    {
        if ($clean) {
            foreach (
                Server::getInstance()->getOnlinePlayers()
                as $sPlayer
            ) {
                if ($sPlayer === $player) {
                    continue;
                }
                if (self::get($sPlayer) === null) {
                    self::open($player);
                }
                $ids[] = spl_object_id($sPlayer);
            }
            $leakedSessionsIds = array_diff(
                array_keys(self::$sessions),
                $ids ?? []
            );
            foreach ($leakedSessionsIds as $leakedSessionsId) {
                self::$sessions[$leakedSessionsId]->close(true);
            }
        }
        return self::$sessions[spl_object_id($player)] = new self(
            $player
        );
    }

    public static function get(
        Player $player
    ) : self
    {
        return self::$sessions[spl_object_id($player)]
            ??= self::open($player, true);
    }

}
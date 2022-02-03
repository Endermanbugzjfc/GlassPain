<?php

namespace Endermanbugzjfc\GlassPain\player;

use Endermanbugzjfc\GlassPain\GlassPain;
use Generator;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\EventPriority;
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
    use TriggeringBlocksManagerTrait;

    protected DataProvider $dataProvider;

    public function nextTriggeringBlockPlace(
        AwaitStd $std
    ) : Generator
    {
        return $std->awaitEvent(
            BlockPlaceEvent::class,
            fn(BlockPlaceEvent $event) => $event->getPlayer() === $this->getPlayer()
                and
                $this->isTriggeringBlock($event->getBlock()),
            false,
            EventPriority::MONITOR,
            false,
            $this->getPlayer()
        );
    }

    public function __construct(
        protected Player $player
    )
    {
        $this->dataProvider = new DataProvider(
            GlassPain::getInstance()->getDataConnector(),
            $this
        );
        Await::f2c(function () {
            try {
                $std = GlassPain::getInstance()->getStd();
                while (true) {
                    $this->coroutine($std, $this->getPlayer());
                }
            } catch (DisposeException) {
                $this->close();
            }
        });
    }

    public function coroutine(
        AwaitStd $std,
        Player   $player
    ) : Generator
    {
        $event = yield from $this->nextTriggeringBlockPlace($std);
        // TODO: Check for permission
    }

    protected function close(
        bool $clean = false
    ) : void
    {
        unset(self::$sessions[spl_object_id($this->getPlayer())]);
    }

    /**
     * @return DataProvider
     */
    public function getDataProvider() : DataProvider
    {
        return $this->dataProvider;
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
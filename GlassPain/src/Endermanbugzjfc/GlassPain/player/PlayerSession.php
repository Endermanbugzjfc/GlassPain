<?php

namespace Endermanbugzjfc\GlassPain\player;

use Endermanbugzjfc\GlassPain\events\TriggeringBlockPlaceEvent;
use Endermanbugzjfc\GlassPain\GlassPain;
use Generator;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\EventPriority;
use pocketmine\player\Player;
use SOFe\AwaitStd\AwaitStd;
use function spl_object_id;

class PlayerSession
{
    use TriggeringBlocksManagerTrait,
        CoroutineTrait,
        PlayerSessionManagerTrait;

    protected DataProvider $dataProvider;

    public function __construct(
        protected Player $player
    )
    {
        $this->dataProvider = new DataProvider(
            GlassPain::getInstance()->getDataConnector(),
            $this
        );
        $this->concurrentLoop(
            fn(AwaitStd $std) => $this->awaitTriggeringBlockPlace($std)
        );
    }

    public function awaitTriggeringBlockPlace(
        AwaitStd $std
    ) : Generator
    {
        $event = yield from $std->awaitEvent(
            BlockPlaceEvent::class,
            fn(BlockPlaceEvent $event) => $event->getPlayer() === $this->getPlayer()
                and
                $this->isTriggeringBlock($event->getBlock()),
            false,
            EventPriority::MONITOR,
            false,
            $this->getPlayer()
        );
        (new TriggeringBlockPlaceEvent(
            $this,
            $event
        ))->call();
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

}
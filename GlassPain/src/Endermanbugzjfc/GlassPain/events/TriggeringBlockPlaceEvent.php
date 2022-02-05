<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\events;

use Endermanbugzjfc\GlassPain\player\PlayerSession;
use pocketmine\event\block\BlockPlaceEvent;

class TriggeringBlockPlaceEvent extends GlassPainEvent
{

    public function __construct(
        protected PlayerSession   $playerSession,
        protected BlockPlaceEvent $blockPlaceEvent
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

    /**
     * @return BlockPlaceEvent
     */
    public function getBlockPlaceEvent() : BlockPlaceEvent
    {
        return $this->blockPlaceEvent;
    }

}
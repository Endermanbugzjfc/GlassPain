<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\player;

use SOFe\InfoAPI\Info;
use SOFe\InfoAPI\InfoAPI;
use SOFe\InfoAPI\NumberInfo;
use SOFe\InfoAPI\PlayerInfo;
use function count;

final class PlayerSessionInfo extends Info
{
    public function __construct(
        protected PlayerSession $value
    )
    {
    }

    public function toString() : string
    {
        return (new PlayerInfo(
            $this->getValue()->getPlayer()
        ))->toString();
    }

    public static function init() : void
    {
        InfoAPI::provideInfo(
            self::class,
            NumberInfo::class,
            "GlassPain.Player.OwnedAnimationsCount",
            fn(self $info) : NumberInfo => new NumberInfo(
                count($info->getValue()->getAnimations())
            )
        );
        InfoAPI::provideFallback(
            self::class,
            PlayerInfo::class,
            fn(self $info) : PlayerInfo => new PlayerInfo(
                $this->getValue()->getPlayer()
            )
        );
    }

    /**
     * @return PlayerSession
     */
    public function getValue() : PlayerSession
    {
        return $this->value;
    }

}

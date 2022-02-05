<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\player;

use Endermanbugzjfc\GlassPain\GlassPain;
use SOFe\InfoAPI\Info;
use SOFe\InfoAPI\InfoAPI;
use SOFe\InfoAPI\NumberInfo;
use SOFe\InfoAPI\RatioInfo;
use SOFe\InfoAPI\StringInfo;

final class PlayerSessionInfo extends Info
{
    public function __construct(
        protected PlayerSession $value
    )
    {
    }

    public function toString() : string
    {
    }

    public static function init() : void
    {

    }

    /**
     * @return PlayerSession
     */
    public function getValue() : PlayerSession
    {
        return $this->value;
    }

}

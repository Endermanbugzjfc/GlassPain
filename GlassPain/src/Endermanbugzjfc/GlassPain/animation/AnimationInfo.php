<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\animation;

use Endermanbugzjfc\GlassPain\Utils;
use pocketmine\Server;
use SOFe\InfoAPI\CommonInfo;
use SOFe\InfoAPI\Info;
use SOFe\InfoAPI\InfoAPI;
use SOFe\InfoAPI\NumberInfo;
use SOFe\InfoAPI\PlayerInfo;
use SOFe\InfoAPI\StringInfo;

final class AnimationInfo extends Info
{
    public function __construct(
        protected AnimationConfig $value
    )
    {
    }

    public function toString() : string
    {
        return $this->getValue()->DisplayName;
    }

    public static function init() : void
    {
        $glassPain = Utils::LOWERCASE_PLUGIN_NAME;
        InfoAPI::provideInfo(
            self::class,
            StringInfo::class,
            "$glassPain.animation.DisplayName",
            fn(self $info) => $info->getValue()->DisplayName
        );
    }

    /**
     * @return AnimationConfig
     */
    public function getValue() : AnimationConfig
    {
        return $this->value;
    }

}

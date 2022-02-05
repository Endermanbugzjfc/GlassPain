<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\animation;

use SOFe\InfoAPI\Info;
use SOFe\InfoAPI\InfoAPI;
use SOFe\InfoAPI\NumberInfo;
use SOFe\InfoAPI\StringInfo;

final class AnimationInfo extends Info
{
    public function __construct(
        protected AnimationBase $value
    )
    {
    }

    public function toString() : string
    {
        return $this->getValue()->getConfig()->DisplayName;
    }

    public static function init() : void
    {
        InfoAPI::provideInfo(
            self::class,
            StringInfo::class,
            "GlassPain.Animation.DisplayName",
            fn(self $info) : StringInfo => new StringInfo(
                $info->getValue()->getConfig()->DisplayName
            )
        );
        InfoAPI::provideInfo(
            self::class,
            NumberInfo::class,
            "GlassPain.Animation.UsersCount",
            fn(self $info) : NumberInfo => new NumberInfo(
                $info->getValue()->getUsersCount()
            )
        );
    }

    /**
     * @return AnimationBase
     */
    public function getValue() : AnimationBase
    {
        return $this->value;
    }

}

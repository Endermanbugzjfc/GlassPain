<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\config;

use Endermanbugzjfc\ConfigStruct\KeyName;
use Endermanbugzjfc\GlassPain\animation\AnimationConfig;
use pocketmine\block\utils\DyeColor;

class ConfigRoot
{

    #[KeyName("default-animation")] public ?AnimationConfig $defaultAnimation = null;

    #[KeyName("panel-form")]
    public ConfigPanelForm $panelForm;

    #[KeyName("overview-form")]
    public ConfigOverviewForm $overviewForm;

    public function __construct()
    {
        $this->panelForm = new ConfigPanelForm();
    }

}
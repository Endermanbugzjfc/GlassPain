<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\config;

use Endermanbugzjfc\ConfigStruct\KeyName;
use Endermanbugzjfc\GlassPain\animation\AnimationConfig;
use pocketmine\block\utils\DyeColor;

class ConfigRoot
{

    #[KeyName("DefaultAnimation")] public ?AnimationConfig $defaultAnimation = null;

    #[KeyName("PanelForm")]
    public ConfigPanelForm $panelForm;

    #[KeyName("OverviewForm")]
    public ConfigOverviewForm $overviewForm;

    public function __construct()
    {
        $this->panelForm = new ConfigPanelForm();
    }

}
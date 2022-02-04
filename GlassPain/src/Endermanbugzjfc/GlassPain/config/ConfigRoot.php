<?php

namespace Endermanbugzjfc\GlassPain\config;

use Endermanbugzjfc\ConfigStruct\KeyName;
use Endermanbugzjfc\GlassPain\animation\AnimationConfig;
use pocketmine\block\utils\DyeColor;

class ConfigRoot
{

    #[KeyName("default-animation")] public ?AnimationConfig $defaultAnimation = null;

    #[KeyName("panel-form")]
    public ConfigPanelForm $panelForm;

    public function __construct()
    {
        $this->panelForm = new ConfigPanelForm();
    }

}
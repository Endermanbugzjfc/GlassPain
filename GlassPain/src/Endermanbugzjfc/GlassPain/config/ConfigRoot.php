<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\config;

use Endermanbugzjfc\ConfigStruct\KeyName;
use Endermanbugzjfc\GlassPain\animation\AnimationConfig;
use pocketmine\block\utils\DyeColor;

class ConfigRoot
{

    public ?AnimationConfig $DefaultAnimation = null;

    public ConfigPanelForm $PanelForm;

    public ConfigOverviewForm $OverviewForm;

    public bool $ShowPanelFormFirst = false;

    public function __construct()
    {
        $this->PanelForm = new ConfigPanelForm();
    }

}
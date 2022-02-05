<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\config;

use Endermanbugzjfc\GlassPain\animation\AnimationConfig;
use Endermanbugzjfc\GlassPain\config\forms\ConfigOverviewForm;
use Endermanbugzjfc\GlassPain\config\forms\ConfigPanelForm;

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
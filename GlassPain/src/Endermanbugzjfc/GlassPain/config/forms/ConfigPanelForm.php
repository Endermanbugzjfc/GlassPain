<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\config\forms;

class ConfigPanelForm extends ConfigForm
{

    public string $AnimationDropdownLabel = <<<EOT
    {BOLD}{GOLD}Animation: 
    EOT;

    public string $AnimationSearchBarLabel = <<<EOT
    {AQUA}Search:
    EOT;

    public string $AnimationSearchBarPlaceholder = <<<EOT
    
    EOT;

    public string $ToggleLabelDisabled = <<<EOT
    {BOLD}{RED}Disabled
    EOT;

    public string $ToggleLabelEnabled = <<<EOT
    {BOLD}{GREEN}Enabled
    EOT;


}
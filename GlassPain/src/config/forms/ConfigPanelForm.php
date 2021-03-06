<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\config\forms;

class ConfigPanelForm extends ConfigForm
{

    public string $ErrorNoAnimations = <<<EOT
    {BOLD}{RED}You haven't own any animations!
    EOT;

    public string $ErrorNoSearchResult = <<<EOT
    {BOLD}{RED}You don't own any animations named "{input}"
    EOT;

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
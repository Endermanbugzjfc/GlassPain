<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\config\forms;

class ConfigPanelForm extends ConfigForm
{

    public string $Content = <<<EOT
    {YELLOW}Selected animation: {WHITE}{Animation DisplayName}
    EOT;

    public string $AnimationDropdownLabel = <<<EOT
    {BOLD}{GOLD}Animation: 
    EOT;

    public string $AnimationSearchBarLabel = <<<EOT
    {AQUA}Search:
    EOT;

    public string $AnimationSearchBarPlaceholder = <<<EOT
    
    EOT;

}
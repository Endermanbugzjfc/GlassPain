<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\config;

class ConfigPanelForm
{

    public string $Title = "{bold}{darkBlue}Glass{gold}Pain";

    public string $Content = <<<EOT
    {yellow}Selected animation: {white}{animation.displayName}
    EOT;


}
<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\config;

class ConfigPanelForm extends ConfigForm
{

    public string $Content = <<<EOT
    {yellow}Selected animation: {white}{animation.displayName}
    EOT;

}
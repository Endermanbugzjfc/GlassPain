<?php


declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\config\forms;

class ConfigAdvancedForm extends ConfigForm
{

    public string $Content;

    public string $SearchBarLabel = "{BOLD}{GOLD}Search:";

    public string $SearchBarPlaceHolder = "Animation name";

}
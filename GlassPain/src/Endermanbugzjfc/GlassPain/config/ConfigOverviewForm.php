<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\config;

class ConfigOverviewForm extends ConfigForm
{

    public string $Content = <<<EOT
    {YELLOW}You owns {Player OwnedAnimationsCount} animations.
    EOT;

    public ?string $AnimationEntryIcon = <<<EOT
    {Animation Icon}
    EOT; // TODO: Null icon


    public string $AnimationEntryText = <<<EOT
    {BOLD}{DARKBLUE}{Animation DisplayName}
    {BLUE}{Animation PopularityCount} players are using
    EOT; // TODO: Popularity info



}
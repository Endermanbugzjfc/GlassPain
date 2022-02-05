<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\config\forms;

class ConfigOverviewForm extends ConfigForm
{

    public string $Content = <<<EOT
    {YELLOW}You owns {Player OwnedAnimationsCount} animations.
    EOT;

    public ?string $AdvancedButtonIcon = ""; // TODO

    public string $AdvancedButtonText = <<<EOT
    {BOLD}{DARKBLUE}Advanced
    {BLUE}(Search bar)
    EOT;


    public ?string $AnimationEntryIcon = <<<EOT
    {Animation Icon}
    EOT; // TODO: Null icon


    public string $AnimationEntryText = <<<EOT
    {BOLD}{DARKBLUE}{Animation DisplayName}
    {BLUE}{Animation UsersCount} players are using
    EOT;



}
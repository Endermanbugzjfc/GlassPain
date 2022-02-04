<?php

namespace Endermanbugzjfc\GlassPain\animation;

use Endermanbugzjfc\ConfigStruct\KeyName;
use Endermanbugzjfc\GlassPain\GlassPain;
use Endermanbugzjfc\GlassPain\Utils;
use pocketmine\block\utils\DyeColor;
use pocketmine\item\ItemBlock;
use pocketmine\item\StringToItemParser;

class AnimationConfig
{

    /**
     * @var string
     * @phpstan-param class-string<AnimationInterface>
     */
    public string $class = "please refer to the information provided by your animation plugin";

    /**
     * @var string Permission to own and apply this animation.
     */
    public string $permission = Utils::LOWERCASE_PLUGIN_NAME . ".animation.";

    /**
     * @var array Key = option name.
     */
    #[KeyName("default-option-values")]
    public array $defaultOptionValues = [
    ];

    /**
     * @var string[]
     */
    #[KeyName("triggering-blocks")]
    public array $triggeringBlocks = [
    ];

    #[KeyName("lock-options")]
    public bool $lockOptions = false;

    #[KeyName("lock-triggering-blocks")]
    public bool $lockTriggeringBlocks = false;

    #[KeyName("display-name")]
    public string $displayName = "formatting reference https://sof3.github.io/InfoAPI/defaults#format";

    public function __construct()
    {
        foreach ([
                     "glass_pane",
                     "hard_glass_pane",
                     "stained_glass_pane",
                     "stained_hardened_glass_pane"
                 ] as $block
        ) {
            foreach (DyeColor::getAll() as $colour) {
                $blocks[] = $colour->name() . "_" . $block;
            }
        }
        $blocks[] = "iron_bars";
        $this->triggeringBlocks = $blocks;
    }

    /**
     * @return ItemBlock[] Key = block ID.
     */
    public function parseTriggeringBlockIds() : array
    {
        foreach ($this->triggeringBlocks as $block) {
            $return[$block] = StringToItemParser::getInstance()->parse(
                $block
            );
        }
        return $return ?? [];
    }

    public function getAnimationInstance() : AnimationInterface
    {

    }

}
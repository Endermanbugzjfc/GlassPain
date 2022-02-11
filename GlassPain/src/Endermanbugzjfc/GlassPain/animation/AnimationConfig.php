<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\animation;

use Endermanbugzjfc\ConfigStruct\KeyName;
use Endermanbugzjfc\GlassPain\Utils;
use pocketmine\block\utils\DyeColor;
use pocketmine\item\ItemBlock;
use pocketmine\item\StringToItemParser;
use SOFe\InfoAPI\FormatInfo;
use SOFe\InfoAPI\InfoAPI;

class AnimationConfig
{

    public string $DisplayName = "formatting reference https://sof3.github.io/InfoAPI/defaults#format";

    /**
     * @var string
     * @phpstan-param class-string<AnimationInterface>
     */
    public string $Class = "please refer to the information provided by your animation plugin";

    /**
     * @var string Permission to own and apply this animation.
     */
    public string $Permission = Utils::LOWERCASE_PLUGIN_NAME . ".animation.";

    /**
     * @var array Key = option name.
     */
    public array $DefaultOptionValues = [
    ];

    /**
     * @var string[]
     */
    public array $TriggeringBlocks = [
    ];

    #[KeyName("LockOptions")]
    public bool $LockOptions = false;

    #[KeyName("LockTriggeringBlocks")]
    public bool $LockTriggeringBlocks = false;

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
        $this->TriggeringBlocks = $blocks;
    }

    /**
     * @return ItemBlock[] Key = block ID.
     */
    public function parseTriggeringBlockIds() : array
    {
        foreach ($this->TriggeringBlocks as $block) {
            $return[$block] = StringToItemParser::getInstance()->parse(
                $block
            );
        }
        return $return ?? [];
    }

    public function parseDisplayName() : string {
        return InfoAPI::resolve(
            $this->DisplayName,
            new FormatInfo()
        );
    }

}
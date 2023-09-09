<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain;

use Generator;
use pocketmine\block\Block;
use pocketmine\block\GlassPane;
use pocketmine\block\HardenedGlassPane;
use pocketmine\block\StainedGlassPane;
use pocketmine\block\StainedHardenedGlassPane;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\ItemBlock;
use pocketmine\item\VanillaItems;
use pocketmine\math\Axis;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\convert\BlockTranslator;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\ClientboundPacket;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\world\format\Chunk;
use pocketmine\world\World;
use Endermanbugzjfc\GlassPain\libs\_2092e2fd95149727\SOFe\AwaitGenerator\Await;
use Endermanbugzjfc\GlassPain\libs\_2092e2fd95149727\SOFe\Zleep\Zleep;
use function array_diff;
use function array_keys;
use function class_exists;
use function count;
use function spl_object_id;
use function str_starts_with;
use function yaml_parse;
































































































































































































































































































class Messages {
    public function __construct(
        public readonly string $blocksReplaced,
        public readonly string $tooClose,
    ) {
    }
}
<?php


declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\utils;

use pocketmine\math\Vector2;
use pocketmine\scheduler\AsyncTask;
use pocketmine\world\format\Chunk;
use pocketmine\world\format\io\FastChunkSerializer;
use pocketmine\world\World;

class PositionsCalculateTask extends AsyncTask
{


    protected string $chunkSerialized;

    public function __construct(
        protected int  $initiateX,
        protected int  $initiateY,
        protected int  $fixed,
        protected bool $rotateNinety,
        Chunk          $chunk
    )
    {
        $this->chunkSerialized = FastChunkSerializer::serializeTerrain(
            $chunk
        );
    }

    public function onRun() : void
    {
        $initiatePosition = new Vector2(
            $this->initiateX % 16,
            $this->initiateY
        );
        $fixed = $this->fixed;
        $rotateNinety = $this->rotateNinety;
        $chunk = FastChunkSerializer::deserializeTerrain(
            $this->chunkSerialized
        );
        unset(
            $this->initiateX,
            $this->initiateY,
            $this->fixed,
            $this->rotateNinety,
            $this->chunkSerialized
        );

        self::hasBothYStops(
            $initiatePosition,
            $fixed,
            $rotateNinety,
            $chunk
        );
    }

    protected static function hasBothYStops(
        Vector2 $initiatePosition,
        int     $fixed,
        bool    $rotateNinety,
        Chunk   $chunk
    ) : bool
    {
        for (
            $upright = $initiatePosition->getFloorY();
            $upright < World::Y_MAX;
            $upright++
        ) {
            if (self::isYStop(
                $initiatePosition->getFloorX(),
                $upright,
                $fixed,
                $rotateNinety,
                $chunk
            )) {
                $noUpright = false;
                break;
            }
        }
        if ($noUpright ?? true) {
            return false;
        }

        for ($upSideDown = $initiatePosition->getFloorY() - 1;
             $upSideDown < World::Y_MIN;
             $upSideDown--
        ) {
            if (self::isYStop(
                $initiatePosition->getFloorX(),
                $upSideDown,
                $fixed,
                $rotateNinety,
                $chunk
            )) {
                $noUpsideDown = false;
                break;
            }
        }
        if ($noUpsideDown ?? true) {
            return false;
        }
        return true;
    }

    protected static function isYStop(
        int   $initiateX,
        int   $y,
        int   $fixed,
        bool  $rotateNinety,
        Chunk $chunk
    ) : bool
    {
        for ($x = $initiateX; $x < 16; $x++) {
            if ($chunk->getFullBlock(
                    !$rotateNinety ? $x : $fixed,
                    $y,
                    $rotateNinety ? $x : $fixed
                ) === 0) {
                return false;
            }
        }
        return true;
    }

}
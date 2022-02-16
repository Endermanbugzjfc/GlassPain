<?php


declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\utils;

use pocketmine\math\Vector2;
use pocketmine\scheduler\AsyncTask;
use pocketmine\world\format\Chunk;
use pocketmine\world\format\io\FastChunkSerializer;

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
    }

}
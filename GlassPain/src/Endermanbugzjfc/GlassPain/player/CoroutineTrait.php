<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\player;

use Closure;
use Endermanbugzjfc\GlassPain\GlassPain;
use SOFe\AwaitStd\Await;

trait CoroutineTrait
{

    private function concurrentLoop(
        Closure $run
    ) : void {
        Await::f2c(function() use
        (
            $run
        ) {
            $std = GlassPain::getInstance()->getStd();
            while (true) {
                if ($run($std) !== null) {
                    return;
                }
            }
        });
    }

}
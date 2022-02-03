<?php

namespace Endermanbugzjfc\GlassPain\player;

use Closure;
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
            while (true) {
                if ($run() !== null) {
                    return;
                }
            }
        });
    }

}
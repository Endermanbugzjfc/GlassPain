<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\player;

use Generator;
use poggit\libasynql\DataConnector;
use SOFe\AwaitStd\Await;
use function bin2hex;

class DataProvider
{

    public function __construct(
        protected DataConnector $connector,
        protected PlayerSession $playerSession
    )
    {
    }

    public function select(
        string $query,
        array  $param
    ) : Generator
    {
        $this->addUniqueIdParam($param);
        $this->connector->executeSelect(
            $query,
            $param,
            yield Await::RESOLVE,
            yield Await::REJECT
        );
        yield Await::ONCE;
    }

    protected function addUniqueIdParam(
        array &$param
    ) : void
    {
        $param["uuid"] = bin2hex(
            $this->playerSession->getPlayer()->getUniqueId()->getBytes()
        );
    }

}
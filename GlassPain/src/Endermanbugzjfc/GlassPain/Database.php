<?php

namespace Endermanbugzjfc\GlassPain;

use Generator;
use poggit\libasynql\DataConnector;
use SOFe\AwaitStd\Await;

class Database
{

    public function __construct(
        protected DataConnector $connector
    )
    {
    }

    public function select(
        string $query,
        array $param
    ) : Generator {
        $this->connector->executeSelect(
            $query,
            $param,
            yield Await::RESOLVE,
            yield Await::REJECT
        );
        yield Await::ONCE;
    }

}
<?php

namespace Endermanbugzjfc\GlassPain;

use poggit\libasynql\DataConnector;

class Database
{

    public function __construct(
        protected DataConnector $connector
    )
    {
    }

}
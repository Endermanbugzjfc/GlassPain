<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain;

use Endermanbugzjfc\ConfigStruct\emit\Emit;
use Endermanbugzjfc\ConfigStruct\parse\Parse;
use Endermanbugzjfc\GlassPain\config\ConfigRoot;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;
use SOFe\AwaitStd\AwaitStd;
use function file_exists;
use function yaml_emit_file;
use function yaml_parse_file;

class GlassPain extends PluginBase
{

    protected AwaitStd $std;

    public ConfigRoot $config;

    protected DataConnector $dataConnector;

    protected function onEnable() : void
    {
        $this->config = new ConfigRoot();
        if (!file_exists(
            $path = $this->getDataFolder() . "config.yml"
        )) {
            yaml_emit_file($path, Emit::emitStruct($this->config));
        } else {
            Parse::parseStruct(
                $this->config,
                yaml_parse_file($path)
            );
        }
        $this->getServer()->getPluginManager()->registerEvents(
            new EventListener(),
            $this
        );
        $this->std = AwaitStd::init($this);

        $this->saveResource($databaseConfig = "database.yml");
        $this->dataConnector = libasynql::create(
            $this,
            (new Config(
                $this->getDataFolder() . $databaseConfig
            ))->getAll(),
            [
                "sqlite" => "sql/sqlite.sql",
                "mysql" => "mysql/mysql.sql"
            ]
        );
    }

    /**
     * @return AwaitStd
     */
    public function getStd() : AwaitStd
    {
        return $this->std;
    }

    /**
     * @return DataConnector
     */
    public function getDataConnector() : DataConnector
    {
        return $this->dataConnector;
    }

    protected function onLoad() : void
    {
        self::$instance = $this;
    }

    protected function onDisable() : void
    {
        $this->getDataConnector()->close();
    }

    protected static self $instance;

    public static function getInstance() : self
    {
        return self::$instance;
    }

}
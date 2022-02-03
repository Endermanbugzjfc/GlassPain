<?php

namespace Endermanbugzjfc\GlassPain;

use Endermanbugzjfc\ConfigStruct\emit\Emit;
use Endermanbugzjfc\ConfigStruct\parse\Parse;
use Endermanbugzjfc\GlassPain\config\ConfigRoot;
use pocketmine\plugin\PluginBase;
use SOFe\AwaitStd\AwaitStd;
use function file_exists;
use function yaml_emit_file;
use function yaml_parse_file;

class GlassPain extends PluginBase
{

    protected AwaitStd $std;

    public ConfigRoot $config;

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
    }

    /**
     * @return AwaitStd
     */
    public function getStd() : AwaitStd
    {
        return $this->std;
    }

    protected function onLoad() : void
    {
        self::$instance = $this;
    }

    protected static self $instance;

    public static function getInstance() : self
    {
        return self::$instance;
    }

}
<?php

namespace supercrafter333\WorldTeleportUI;

use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use supercrafter333\WorldTeleportUI\Commands\wtpCommand;

/**
 * Class WorldTeleportUI
 * @package supercrafter333\WorldTeleportUI
 */
class WorldTeleportUI extends PluginBase
{

    /**
     * @var
     */
    protected static $instance;
    /**
     * @var
     */
    protected $worldCfg;
    /**
     * @var
     */
    protected $config;

    /**
     *
     */
    public function onEnable()
    {
        self::$instance = $this;
        $this->saveResource("worldList.yml");
        $this->saveResource("config.yml");
        $this->worldCfg = new Config($this->getDataFolder() . "worldList.yml", Config::YAML);
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->getServer()->getCommandMap()->register("WorldTeleportUI", new wtpCommand("wtp"));
    }

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        return self::$instance;
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @return Config
     */
    public function getWorldCfg(): Config
    {
        return $this->worldCfg;
    }

    /**
     * @param $worldName
     * @return bool
     */
    public function isWorldSet(string $worldName): bool
    {
        return $this->getWorldCfg()->exists($worldName);
    }

    /**
     * @param $worldName
     * @return mixed|null
     */
    public function getRealWorldName($worldName)
    {
        if ($this->isWorldSet($worldName)) {
            $world = $this->getWorldCfg()->get($worldName)["worldName"];
            if ($this->getServer()->isLevelGenerated($worldName)) {
                return $world;
            }
        }
        return null;
    }

    /**
     * @param $worldName
     * @return mixed|null
     */
    public function getUIText($worldName)
    {
        if ($this->isWorldSet($worldName)) {
            return $this->getWorldCfg()->get($worldName)["uiText"];
        }
        return null;
    }

    /**
     * @param $worldName
     * @return Level|null
     */
    public function getWorld($worldName)
    {
        if ($this->getRealWorldName($worldName) !== null) {
            if ($this->getServer()->isLevelLoaded($worldName)) {
                $world = $this->getServer()->getLevelByName($worldName);
                return $world;
            } else {
                $this->getServer()->loadLevel($worldName);
                $world = $this->getServer()->getLevelByName($worldName);
                return $world;
            }
        }
        return null;
    }

    /**
     * @param Player $player
     * @param Level $world
     */
    public function teleportToWorld(Player $player, Level $world)
    {
        $player->teleport($world->getSafeSpawn());
    }
###########################################################################################################
## WorldTeleportUI, licensed under the Apache License 2.0! Made by supercrafter333 with love in germany! ##
###########################################################################################################
}
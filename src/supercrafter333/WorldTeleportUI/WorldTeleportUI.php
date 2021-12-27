<?php

namespace supercrafter333\WorldTeleportUI;

use pocketmine\utils\SingletonTrait;
use pocketmine\world\World;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use supercrafter333\WorldTeleportUI\Commands\wtpCommand;

/**
 * Class WorldTeleportUI
 * @package supercrafter333\WorldTeleportUI
 */
class WorldTeleportUI extends PluginBase
{
    use SingletonTrait;

    /**
     * @var
     */
    protected Config $worldCfg;
    /**
     * @var
     */
    protected Config $config;
    
    protected function onLoad(): void
    {
        self::setInstance($this);
    }

    /**
     * onEnable function
     */
    public function onEnable():void
    {
        $this->saveResource("worldList.yml");
        $this->saveResource("config.yml");
        $this->worldCfg = new Config($this->getDataFolder() . "worldList.yml", Config::YAML);
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->getServer()->getCommandMap()->register("WorldTeleportUI", new wtpCommand("wtp"));
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
    public function getRealWorldName($worldName): mixed
    {
        if ($this->isWorldSet($worldName)) {
            $world = $this->getWorldCfg()->get($worldName)["worldName"];
            if ($this->getServer()->getWorldManager()->isWorldGenerated($worldName)) {
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
     * @return World|null
     */
    public function getWorld($worldName): ?World
    {
        if ($this->getRealWorldName($worldName) !== null) {
            if ($this->getServer()->getWorldManager()->isWorldLoaded($worldName)) {
                $world = $this->getServer()->getWorldManager()->getWorldByName($worldName);
                return $world;
            } else {
                $this->getServer()->getWorldManager()->loadWorld($worldName);
                $world = $this->getServer()->getWorldManager()->getWorldByName($worldName);
                return $world;
            }
        }
        return null;
    }

    /**
     * @param Player $player
     * @param World $world
     */
    public function teleportToWorld(Player $player, World $world)
    {
        $player->teleport($world->getSafeSpawn());
    }
###########################################################################################################
## WorldTeleportUI, licensed under the Apache License 2.0! Made by supercrafter333 with love in germany! ##
###########################################################################################################
}
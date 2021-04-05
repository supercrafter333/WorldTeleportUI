<?php

namespace supercrafter333\WorldTeleportUI\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use supercrafter333\WorldTeleportUI\UIs;
use supercrafter333\WorldTeleportUI\WorldTeleportUI;

/**
 * Class wtpCommand
 * @package supercrafter333\WorldTeleportUI\Commands
 */
class wtpCommand extends Command implements PluginIdentifiableCommand
{

    /**
     * @var WorldTeleportUI
     */
    private $plugin;

    /**
     * wtpCommand constructor.
     * @param string $name
     * @param string $description
     * @param string|null $usageMessage
     * @param array $aliases
     */
    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        $this->plugin = WorldTeleportUI::getInstance();
        parent::__construct("wtp", "Open WorldTeleportUI's UI!", $usageMessage, ["worldteleport", "wtpui", "worldteleportui"]);
    }

    /**
     * @param CommandSender $s
     * @param string $commandLabel
     * @param array $args
     */
    public function execute(CommandSender $s, string $commandLabel, array $args): void
    {
        if (!$s instanceof Player) {
            $s->sendMessage(WorldTeleportUI::getInstance()->getConfig()->get("msg-Only-In-Game"));
            return;
        }
        $ui = new UIs($this->plugin);
        $ui->openMenuUI($s);
    }

    /**
     * @return Plugin
     */
    public function getPlugin(): Plugin
    {
        return $this->plugin;
    }
}
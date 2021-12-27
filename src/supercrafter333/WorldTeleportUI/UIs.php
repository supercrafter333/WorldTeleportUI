<?php

namespace supercrafter333\WorldTeleportUI;

use pocketmine\player\Player;
use Vecnavium\FormsUI\SimpleForm;

/**
 * Class UIs
 * @package supercrafter333\WorldTeleportUI
 */
class UIs
{

    /**
     * @var WorldTeleportUI
     */
    private $plugin;

    /**
     * UIs constructor.
     * @param WorldTeleportUI $plugin
     */
    public function __construct(WorldTeleportUI $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * @param Player $player
     * @return SimpleForm|void
     */
    public function openMenuUI(Player $player)
    {
        $pl = $this->plugin;
        foreach ($pl->getWorldCfg()->getAll() as $item => $all) {
            if ($pl->getRealWorldName($item) === null) {
                $player->sendMessage($pl->getConfig()->get("msg-teleport-not-save"));
                $pl->getLogger()->debug("UI-DEBUG: The variable 'worldName' in worldList.yml ist not save! Can't open UI!");
                return;
            }
        }
        $form = new SimpleForm([$this, 'submitMenuUI']);
        $form->setTitle($pl->getConfig()->get("UI-Title"));
        $form->setContent($pl->getConfig()->get("UI-Content"));
        foreach ($pl->getWorldCfg()->getAll() as $item => $woorld) {
            $world = $woorld["worldName"];
            $uiTxt = $pl->getUIText($item);
            $form->addButton($uiTxt, -1, "", $item);
        }
        $form->sendToPlayer($player);
        return $form;
    }

    /**
     * @param Player $player
     * @param $woorld
     */
    public function submitMenuUI(Player $player, $woorld)
    {
        if ($woorld === null) {
            return;
        }
        $worldName = $this->plugin->getRealWorldName($woorld);
        if ($this->plugin->getWorld($worldName) !== null) {
            $world = $this->plugin->getWorld($worldName);
            $this->plugin->teleportToWorld($player, $world);
            $player->sendMessage($this->plugin->getConfig()->get("msg-successfully-teleported"));
        } else {
            $player->sendMessage($this->plugin->getConfig()->get("msg-teleport-not-save"));
        }
    }
###########################################################################################################
## WorldTeleportUI, licensed under the Apache License 2.0! Made by supercrafter333 with love in germany! ##
###########################################################################################################
}
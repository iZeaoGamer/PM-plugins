<?php

namespace Core\Tasks;

use Core\Loader;
use pocketmine\scheduler\PluginTask;
use pocketmine\utils\TextFormat;

class Pvp extends PluginTask {

    public $pvp = 20000;

    public function __construct(Loader $owner) {
        parent::__construct($owner);
        $this->plugin = $owner;
    }

    public function onRun($task) {
        $this->pvp--;
        foreach ($this->getOwner()->getServer()->getOnlinePlayers() as $p) {

            $p->sendPopup(TextFormat::GOLD . "Teleportation will occur in " . TextFormat::YELLOW . gmdate("i:s", $this->pvp) . "\n" .
                    TextFormat::GOLD . "        X: " . TextFormat::YELLOW . round($p->getX()) . TextFormat::GOLD . " Y: " . TextFormat::YELLOW . round($p->getY()) . TextFormat::GOLD . " Z: " . TextFormat::YELLOW . round($p->getZ()) . "\n" . 
                    TextFormat::GOLD . "      Kills:" .  TextFormat::YELLOW . " 0 " . TextFormat::GOLD . "Blocks Mined:" . TextFormat::YELLOW . " 0");
        }

        if ($this->pvp === 1) {
            $this->owner->getServer()->getScheduler()->cancelTask($this->getTaskId());
            $this->owner->getServer()->broadcastMessage(TextFormat::YELLOW . "Teleporting...");
        }
    }

}

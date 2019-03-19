<?php

namespace Core\Tasks;

use Core\Loader;
use pocketmine\network\protocol\SetDifficultyPacket;
use pocketmine\scheduler\PluginTask;
use pocketmine\utils\TextFormat;

class Grace extends PluginTask {

    public $gracetime = 1200;

    public function __construct(Loader $owner) {
        parent::__construct($owner);
        $this->plugin = $owner;
    }

    public function onRun($task) {
        $this->gracetime--;

        foreach ($this->owner->getServer()->getOnlinePlayers() as $p) {

            $p->sendPopup(TextFormat::GOLD . " Grace ends in " . TextFormat::YELLOW . gmdate("i:s", $this->gracetime) . "\n" .
                    TextFormat::GOLD . "  X: " . TextFormat::YELLOW . round($p->getX()) . TextFormat::GOLD . " Y: " . TextFormat::YELLOW . round($p->getY()) . TextFormat::GOLD . " Z: " . TextFormat::YELLOW . round($p->getZ()) . "\n" . 
                    TextFormat::GOLD . "Kills:" .  TextFormat::YELLOW . " 0 " . TextFormat::GOLD . "Blocks Mined:" . TextFormat::YELLOW . " 0");
        }

        if ($this->gracetime === 1) {

            $this->owner->getServer()->getScheduler()->cancelTask($this->getTaskId());

            $pk = new SetDifficultyPacket();
            $pk->difficulty = $this->owner->getServer()->getDifficulty();
            $this->owner->getServer()->setConfigInt("difficulty", 1);

            $this->owner->getServer()->broadcastMessage(TextFormat::RED . "PvP enabled! Best of luck to you!");

            $this->owner->StartPvp();
        }
    }

}

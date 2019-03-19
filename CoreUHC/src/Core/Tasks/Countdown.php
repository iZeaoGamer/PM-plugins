<?php

namespace Core\Tasks;

use Core\Loader;
use pocketmine\network\protocol\SetDifficultyPacket;
use pocketmine\scheduler\PluginTask;
use pocketmine\utils\TextFormat;

class Countdown extends PluginTask {

    public $timer = 60;

    public function __construct(Loader $owner) {
        parent::__construct($owner);
        $this->plugin = $owner;
    }

    public function onRun($task) {
        $this->timer--;

        /*if ($this->timer === 60) {

            $pk = new SetDifficultyPacket();
            $pk->difficulty = $this->owner->getServer()->getDifficulty();
            $this->owner->getServer()->setConfigInt("difficulty", 0);

            $this->owner->getServer()->broadcastMessage(TextFormat::GREEN . "Disabled PvP");
        }

        if ($this->timer === 30) {
            $this->owner->getServer()->broadcastMessage(TextFormat::GOLD . "Welcome to (HidingSpoilers)!");
            $this->owner->getServer()->broadcastMessage(TextFormat::GOLD . "Before your game can start we have a few rules.");
        }

        if ($this->timer === 28) {
            $this->owner->getServer()->broadcastMessage(TextFormat::GOLD . "Rule #1: Any sort of modification is banned. Caught with them and you are banned!");
            $this->owner->getServer()->broadcastMessage(TextFormat::GOLD . "Rule #2: 20 minutes of grace time in each UHC.");
        }*/

        $level = $this->owner->getServer()->getLevelByName("UHC");

        foreach ($this->getOwner()->getServer()->getOnlinePlayers() as $p) {

            $p->sendPopup(TextFormat::GOLD . "Starting in " . TextFormat::YELLOW . gmdate("i:s", $this->timer));
        }

        if ($this->timer === 1) {

            $this->owner->getServer()->getScheduler()->cancelTask($this->getTaskId());

            foreach ($this->owner->getServer()->getOnlinePlayers() as $p) {

                $p->teleport($level->getSafeSpawn());
                $p->sendMessage(TextFormat::RED . "Teleported you to the UHC world!");

                $this->owner->startSecondTimer();
            }
        }
    }

}

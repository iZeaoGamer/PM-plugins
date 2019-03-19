<?php

namespace Core;

use Core\Listener\EventListener;
use Core\Tasks\Countdown;
use Core\Tasks\Grace;
use Core\Tasks\Pvp;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Loader extends PluginBase implements Listener {

    public function onEnable() {
        @mkdir($this->getDataFolder());
        $this->config = new Config($this->getDataFolder() . "/scenarios.yml", CONFIG::YAML, array(
            "cutclean" => true
        ));
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        //$this->getServer()->getScheduler()->scheduleRepeatingTask(new \Core\Tasks\Restart($this), 20)->getTaskId();
    }

    public function startSecondTimer() {
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new Grace($this), 25)->getTaskId();
    }

    public function startPvp() {
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new Pvp($this), 25)->getTaskId();
    }

    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
        $command = strtolower($cmd->getName());
        $server = $this->getServer();
        switch ($command) {
            //Main UHC command
            case "uhc":
                if (count($args) === 0) {
                    $sender->sendMessage(TextFormat::RED . "Usage: /uhc [start]");

                    return false;
                }
                switch ($args[0]) {
                    case "start":
                        foreach ($server->getOnlinePlayers() as $p) {
                            $p->getInventory()->clearAll();
                            $p->setGamemode(0);
                            $p->setFood(20);
                            $p->setHealth(20);
                        }
                        $server->getScheduler()->scheduleRepeatingTask(new Countdown($this), 25)->getTaskId();
                        break;
                }
            //Main scenario command. TODO: Clean it up
            case "scenario":
                if (count($args) === 0) {
                    $sender->sendMessage(TextFormat::RED . "Usage: /scenario [set:rm:list]");

                    return false;
                }
                switch ($args[0]) {
                    //Code for setting a scenario.
                    case "set":
                        switch ($args[1]) {
                            case "cutclean":
                                if ($this->config->get("cutclean") === true) {
                                    $sender->sendMessage(TextFormat::RED . "Scenario is already active!");
                                    return;
                                } else {
                                    $this->config->set("cutclean", true);
                                    $this->config->save();
                                    $this->config->reload();
                                    $sender->sendMessage(TextFormat::GREEN . "Cutclean enabled!");
                                    return true;
                                }
                        }
                    //Code for removing a scenario.
                    case "rm":
                        switch ($args[1]) {
                            case "cutclean":
                                if ($this->config->get("cutclean") === false) {
                                    $sender->sendMessage(TextFormat::RED . "Scenario is already disabled!");
                                } else {
                                    $this->config->set("cutclean", false);
                                    $this->config->save();
                                    $this->config->reload();
                                    $sender->sendMessage(TextFormat::GREEN . "Cutclean disabled!");
                                } 
                                return true;
                        }                   
                }
        }
    }

}

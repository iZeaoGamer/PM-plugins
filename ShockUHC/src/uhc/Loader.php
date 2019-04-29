<?php

namespace uhc;

use pocketmine\plugin\PluginBase;
use pocketmine\item\ItemFactory;
use uhc\{managers\CommandManager, managers\ScenarioManager, timers\UHCTimer};

class Loader extends PluginBase{

	public $gameStatus = UHCTimer::STATUS_WAITING;
	public $queue = [];
	public $eliminations = [];
	public $globalMute = false;
	/** @var ScenarioManager */
	public $scenarioManager;

	public function onEnable(){
		$this->getScheduler()->scheduleRepeatingTask(new UHCTimer($this), 20);
		new CommandManager($this);
		new EventListener($this);
		$this->scenarioManager = new ScenarioManager($this);
	}

	public function addElimination(UHCPlayer $player){
		if(isset($this->eliminations[$player->getName()])){
			$this->eliminations[$player->getName()] = $this->eliminations[$player->getName()] + 1;
		}else{
			$this->eliminations[$player->getName()] = 1;
		}
	}

	public function getEliminations(UHCPlayer $player){
		if(isset($this->eliminations[$player->getName()])){
			return $this->eliminations[$player->getName()];
		}else{
			return $this->eliminations[$player->getName()] = 0;
		}
	}
}
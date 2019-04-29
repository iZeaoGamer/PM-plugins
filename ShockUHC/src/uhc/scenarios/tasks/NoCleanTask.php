<?php

namespace uhc\scenarios\tasks;

use network\NetworkLoader;
use uhc\Loader;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\scheduler\Task;
use uhc\UHCPlayer;

class NoCleanTask extends Task{

	private $player;

	public function __construct(Loader $plugin, UHCPlayer $player){
		$this->player = $player;
		$player->setNoCleanTime(20);
		$this->player->setNoCleanActive(true);
		$this->setHandler($plugin->getScheduler()->scheduleRepeatingTask($this, 20));
	}

	public function onRun(int $currentTick){
		$player = $this->player;
		if($player instanceof Player){
			if($player->hasNoClean()){
				if($player->getNoCleanTime() <= 0){
					$player->sendMessage(NetworkLoader::selectPrefix("NoClean") . "You are no longer invincible!");
					$player->removeLine(6);
					$player->setNoCleanActive(false);

					return;
				}else{
					$player->setScoreboardLine(6, " §eNoClean: §f" . $player->getNoCleanTime());
				}
				$player->setNoCleanTime($player->getNoCleanTime() - 1);
			}
		}else{
			$this->cancel();
		}
	}

	public function cancel(){
		$this->getHandler()->cancel();
	}
}
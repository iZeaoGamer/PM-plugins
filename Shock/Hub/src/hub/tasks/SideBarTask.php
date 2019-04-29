<?php

namespace hub\tasks;

use network\NetworkPlayer;
use pocketmine\scheduler\Task;

use hub\Loader;
use network\utils\Scoreboard;

class SideBarTask extends Task{
	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		$this->plugin = $plugin;
	}

	public function onRun($currentTick){
		foreach($this->plugin->getConfig()->getAll() as $serverName => $data){
			$this->plugin->getServer()->getAsyncPool()->submitTask(new CheckStatusTask($data[0]));
		}
		/** @var NetworkPlayer $player */
		foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
			if(!$player->spawned) return;
			$scoreboard = new Scoreboard($player);
			$scoreboard->setTitle("§6§lShock§fHub§r");

			$scoreboard->setLine(1, "§7---------------------");
			$scoreboard->setLine(2, " " . $player->getName());
			$scoreboard->setEmptyLine(3);
			$scoreboard->setLine(4, " §6Rank:");
			$scoreboard->setLine(5, " §f" . ucfirst($player->getRank()));
			$scoreboard->setEmptyLine(6);
			$scoreboard->setLine(7, " §6play.theshocknetwork.com");
			$scoreboard->setLine(8, "§7--------------------- ");
		}
	}
}
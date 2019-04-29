<?php

namespace practice\command;

use practice\PracticePlayer;
use practice\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\utils\TextFormat;

class SpawnCommand extends PluginCommand{

	private $plugin;

	public function __construct(Loader $plugin){
		parent::__construct("spawn", $plugin);
		$this->setPlugin($plugin);
	}

	public function setPlugin(Loader $plugin){
		$this->plugin = $plugin;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if($sender instanceof PracticePlayer){
			if($sender->isPlaying()){
				$sender->sendMessage(TextFormat::RED . "You are already in a match!");
				return;
			}

			$sender->giveLobbyItems();
			$sender->sendMessage(TextFormat::GREEN . "You have been teleported to spawn");
			$sender->teleport($this->plugin->getServer()->getDefaultLevel()->getSafeSpawn());
		}
	}
}
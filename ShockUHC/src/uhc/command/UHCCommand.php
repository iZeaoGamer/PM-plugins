<?php

namespace uhc\command;

use network\NetworkLoader;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use uhc\Loader;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use uhc\timers\UHCTimer;
use uhc\UHCPlayer;

class UHCCommand extends PluginCommand{
	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		parent::__construct("uhc", $plugin);
		$this->plugin = $plugin;
		$this->setPermission("shock.command.uhc");
		$this->setUsage("/uhc [start:stop]");
	}


	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
		if(!$this->testPermission($sender)){
			return true;
		}
		if(empty($args)){
			throw new InvalidCommandSyntaxException();
		}

		switch($args[0]){
			case "start":
				$this->plugin->gameStatus = UHCTimer::STATUS_COUNTDOWN;
				$sender->sendMessage(NetworkLoader::selectPrefix("UHC") . "You have started the UHC!");
				break;
				//TODO: Implement /uhc stop again.
		}

		return true;
	}
}
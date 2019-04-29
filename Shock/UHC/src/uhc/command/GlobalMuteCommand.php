<?php

namespace uhc\command;

use pocketmine\command\utils\InvalidCommandSyntaxException;
use uhc\Loader;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\utils\TextFormat;

class GlobalMuteCommand extends PluginCommand{
	/** @var Loader $plugin */
	private $plugin;

	public function __construct(Loader $plugin){
		parent::__construct("globalmute", $plugin);
		$this->plugin = $plugin;
		$this->setPermission("shock.command.globalmute");
		$this->setUsage("/globalmute [on:off]");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) < 1){
			throw new InvalidCommandSyntaxException();
		}

		switch($args[0]){
			case "on":
				if(!$this->plugin->globalMute){
					$this->plugin->globalMute = true;
					$this->plugin->getServer()->broadcastMessage(TextFormat::GREEN . "Global Mute has been enabled by an admin!");
					$sender->sendMessage("You have successfully enabled Global Mute!");
				}else{
					$sender->sendMessage(TextFormat::RED . "Global Mute is already enabled!");
				}
				break;
			case "off":
				if($this->plugin->globalMute){
					$this->plugin->globalMute = false;
					$this->plugin->getServer()->broadcastMessage(TextFormat::GREEN . "Global Mute has been disabled by an admin!");
					$sender->sendMessage(TextFormat::GREEN . "You have successfully disabled Global Mute!");
				}else{
					$sender->sendMessage(TextFormat::RED . "Global Mute is already disabled!");
				}
				break;
		}

		return true;
	}
}
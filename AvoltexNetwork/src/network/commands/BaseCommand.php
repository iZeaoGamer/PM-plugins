<?php
declare(strict_types=1);

/*
 * © AppleDevelops 2017
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author AppleDevelops
 *
*/

namespace network\commands;

use network\NetworkCore;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class BaseCommand extends Command implements PluginIdentifiableCommand{

	public $plugin;

	public function __construct(NetworkCore $plugin, string $name, string $description, string $usageMessage, array $aliases = []){
		parent::__construct($name, $description, $usageMessage, $aliases);
		$this->plugin = $plugin;
	}


	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if($this->testPermission($sender)){
			$result = $this->onExecute($sender, $args);

			if(is_string($result)){
				$sender->sendMessage($result);
			}

			return true;
		}

		return false;
	}


	public function onExecute(CommandSender $sender, array $args){

	}


	public function getPlugin(): Plugin{
		return $this->plugin;
	}

	public function sendUsageMessage(): string{
		return TextFormat::RED . "An error has occurred.\nCommand usage: " . $this->usageMessage;
	}
}
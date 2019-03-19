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

use network\interfaces\Groups;
use network\NetworkCore;
use network\utils\Ranks;
use network\tasks\UpdateGroupTask;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

class SetGroupCommand extends BaseCommand{
	public function __construct(NetworkCore $plugin){
		parent::__construct($plugin, "setgroup", "Sets a player's group", "/setgroup [player] [group]", []);
		$this->setPermission("avoltex.command.setgroup");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if($sender->hasPermission("avoltex.command.setgroup")){
			$player = $sender->getServer()->getPlayer($args[0]);
			$group = strtolower($args[1]);
			if(in_array($group, Groups::GROUPS)){
				$upper = strtoupper($args[1]);
				$this->plugin->getUser()->setGroup($player, $group);
				$sender->sendMessage("Successfully set " . $player->getName() . " to the group {$upper}!");
				$player->sendMessage("Your group has been set to {$upper}!");
				return true;
			}else{
				throw new InvalidCommandSyntaxException();
			}
		}
		return true;
	}
}
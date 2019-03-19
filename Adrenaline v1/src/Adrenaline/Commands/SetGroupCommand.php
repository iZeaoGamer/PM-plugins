<?php

declare(strict_types=1);

namespace Adrenaline\Commands;

use Adrenaline\BaseFiles\BaseCommand;
use Adrenaline\Loader;
use pocketmine\command\CommandSender;

/**
 * Class SetGroupCommand
 *
 * @package Adrenaline\Commands
 */
class SetGroupCommand extends BaseCommand {
	/**
	 * SetGroupCommand constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "setgroup", "Sets a player's group", "/setgroup [player] [group]", []);
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		$player = $sender->getServer()->getPlayer($args[0]);
		$data = $this->getLoader()->getAPI()->getAuthManager()->getPlayer($player);
		if($data["group"] === $args[1]){
			$sender->sendMessage($player->getName() . " is already set to rank " . $args[1]);
			return false;
		}

		if(in_array($args[1], $this->getLoader()->getAPI()->getAvaliableGroups())){
			$data["group"] = $args[1];
			$this->getLoader()->getAPI()->getAuthManager()->savePlayer($player, $data);
			$sender->sendMessage("Successfully set " . $player->getName() . " to the group " . $args[1]);
			$player->sendMessage("Your group has been set to " . $args[1]);
		}else{
			$sender->sendMessage("Invalid group");
		}
		return false;
	}
}
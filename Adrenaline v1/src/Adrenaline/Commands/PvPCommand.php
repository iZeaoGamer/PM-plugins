<?php

declare(strict_types=1);

namespace Adrenaline\Commands;

use Adrenaline\BaseFiles\BaseCommand;
use Adrenaline\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;

/**
 * Class PvPCommand
 *
 * @package Adrenaline\Commands
 */
class PvPCommand extends BaseCommand {
	/**
	 * PvPCommand constructor.
	 *
	 * @param Loader $loader
	 */
	public function __construct(Loader $loader){
		parent::__construct($loader, "pvp", "Enable and disable PvP", "/pvp [on|off]", []);
		$this->setPermission("adrenaline.command.pvp");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		if($sender->hasPermission($this->getPermission())){
			if(count($args) < 1){
				$sender->sendMessage($this->getUsage());
				return false;
			}

			if(isset($args[0])){
				switch($args[0]){
					case "on":
						$this->getLoader()->getAPI()->setDifficulty(1);
						Server::getInstance()->broadcastMessage($this->getLoader()->getAPI()->getPrefix() . "PvP has been enabled!");
						break;

					case "off":
						$this->getLoader()->getAPI()->setDifficulty();
						Server::getInstance()->broadcastMessage($this->getLoader()->getAPI()->getPrefix() . "PvP has been disabled!");
						break;
				}
			}else{
				$sender->sendMessage($this->getUsage());
			}
		}else{
			$sender->sendMessage($this->sendNoPermission());
		}
		return false;
	}

	/**
	 * @param Player $player
	 *
	 * @return array
	 *
	 * public function generateCustomCommandData(Player $player){
	 * $commandData = parent::generateCustomCommandData($player);
	 * $commandData["overloads"]["default"]["input"]["parameters"] = [
	 * 0 => [
	 * "type" => "stringenum",
	 * "name" => "options",
	 * "optional" => false,
	 * "enum_values" => ["on", "off"]
	 * ]
	 * ];
	 * return $commandData;
	 * }*/
}
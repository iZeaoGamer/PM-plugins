<?php
declare(strict_types=1);

namespace Adrenaline\Commands;

use Adrenaline\BaseFiles\BaseCommand;
use Adrenaline\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

/**
 * Class ScenarioCommand
 *
 * @package Adrenaline\Commands
 */
class ScenarioCommand extends BaseCommand {
	/**
	 * ScenarioCommand constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "scenario", "Set and remove scenarios", "/scenario [set|list|rem] [scenario]", []);
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		if(isset($args[0])){
			if(isset($args[1])){
				switch($args[0]){
					case "set":
						foreach($this->getPlugin()->getAPI()->getScenarioManager()->getScenarios() as $scenario){
							if($scenario->stringMatches($args[1])){
								if(!$scenario->isActive()){
									$sender->sendMessage($this->getLoader()->getAPI()->getPrefix() . $scenario->getName() . " has been enabled!");
									$scenario->setActive(true);
									return true;
								}else{
									$sender->sendMessage($this->getLoader()->getAPI()->getPrefix() . TextFormat::RED . $scenario->getName() . " is already enabled!");
									return false;
								}
							}
						}
						break;
					case "rem":
						foreach($this->getPlugin()->getAPI()->getScenarioManager()->getScenarios() as $scenario){
							if($scenario->stringMatches($args[1])){
								if($scenario->isActive()){
									$sender->sendMessage($this->getLoader()->getAPI()->getPrefix() . $scenario->getName() . " has been disabled!");
									$scenario->setActive(false);
									return true;
								}else{
									$sender->sendMessage($this->getLoader()->getAPI()->getPrefix() . TextFormat::RED . $scenario->getName() . " is already disabled!");
									return false;
								}
							}
						}
						break;
				}
			}else{
				switch($args[0]){
					case "list":
						foreach($this->getPlugin()->getAPI()->getScenarioManager()->getScenarios() as $scenario){
							$active = $scenario->isActive() ? TextFormat::AQUA . "ENABLED" : TextFormat::RED . "DISABLED";
							$sender->sendMessage(TextFormat::GOLD . "[" . $scenario->getName() . "] " . $active);
						}
				}
			}
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
	 * "enum_values" => ["list", "set", "rem"]
	 * ],
	 * 1 => [
	 * "type" => "stringenum",
	 * "name" => "scenarios",
	 * "optional" => true,
	 * "enum_values" => ["BloodDiamond", "CutClean", "Diamondless", "Windows10"]
	 * ]
	 * ];
	 * return $commandData;
	 * }*/
}
<?php

namespace uhc\command;

use pocketmine\command\utils\InvalidCommandSyntaxException;
use uhc\Loader;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\utils\TextFormat;

class ScenariosCommand extends PluginCommand{
	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		parent::__construct("scenarios", $plugin);
		$this->plugin = $plugin;
		$this->setPermission("shock.command.scenarios");
		$this->setUsage("/scenarios [list:set:rem] <scenarioName>");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
		if(count($args) <= 0){
			throw new InvalidCommandSyntaxException();
		}

		switch($args[0]){
			case "set":
				if(!$this->testPermission($sender)){
					return true;
				}
				if(isset($args[1])){
					foreach($this->plugin->scenarioManager->getScenarios() as $scenario){
						if($scenario->stringMatches($args[1])){
							if(!$scenario->isActive()){
								$scenario->setActive(true);
								$sender->sendMessage("Activated " . $scenario->getName());
							}else{
								$sender->sendMessage("Scenario is already active!");
							}
						}
					}
				}else{
					throw new InvalidCommandSyntaxException();
				}
				break;
			case "rem":
				if(!$this->testPermission($sender)){
					return true;
				}
				if(isset($args[1])){
					foreach($this->plugin->scenarioManager->getScenarios() as $scenario){
						if($scenario->stringMatches($args[1])){
							if($scenario->isActive()){
								$scenario->setActive(false);
								$sender->sendMessage("Deactivated " . $scenario->getName());
							}else{
								$sender->sendMessage("Scenario is already deactivated!");
							}
						}
					}
				}else{
					throw new InvalidCommandSyntaxException();
				}
				break;
			case "list":
				foreach($this->plugin->scenarioManager->getScenarios() as $scenario){
					$active = $scenario->isActive() ? TextFormat::GREEN . "ACTIVE" : TextFormat::RED . "INACTIVE";
					$sender->sendMessage(TextFormat::GRAY . $scenario->getName() . " - " . $active);
				}
				break;
		}

		return true;
	}
}
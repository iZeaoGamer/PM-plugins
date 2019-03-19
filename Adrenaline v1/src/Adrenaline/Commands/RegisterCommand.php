<?php
declare(strict_types=1);

namespace Adrenaline\Commands;

use Adrenaline\BaseFiles\BaseCommand;
use Adrenaline\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

/**
 * Class RegisterCommand
 *
 * @package Adrenaline\Commands
 */
class RegisterCommand extends BaseCommand {
	/**
	 * RegisterCommand constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "register", "Register an account", "/register [password]", []);
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		if($sender instanceof Player){
			if($this->getLoader()->getAPI()->getAuthManager()->isPlayerRegistered($sender)){
				$sender->sendMessage(TextFormat::RED . "This account is already registered");

				return true;
			}

			$password = implode(" ", $args);

			if($this->getLoader()->getAPI()->getAuthManager()->registerPlayer($sender, $password) and $this->getLoader()->getAPI()->getAuthManager()->authenticatePlayer($sender)){
				$sender->sendMessage("Registered!");
				return true;
			}else{
				$sender->sendMessage(TextFormat::RED . "Registration error");
				return true;
			}
		}else{
			$sender->sendMessage(TextFormat::RED . "This command only works in-game.");

			return true;
		}
	}

	/*public function generateCustomCommandData(Player $player){
		$commandData = parent::generateCustomCommandData($player);
		$commandData["overloads"]["default"]["input"]["parameters"] = [
			0 => [
				"type" => "rawtext",
				"name" => "register",
				"optional" => false,
				"enum_values" => []
			]
		];
		return $commandData;
	}*/
}
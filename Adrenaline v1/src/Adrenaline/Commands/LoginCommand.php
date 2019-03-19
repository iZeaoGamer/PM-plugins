<?php
declare(strict_types=1);

namespace Adrenaline\Commands;

use Adrenaline\BaseFiles\BaseCommand;
use Adrenaline\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

/**
 * Class LoginCommand
 *
 * @package Adrenaline\Commands
 */
class LoginCommand extends BaseCommand {
	/**
	 * LoginCommand constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "login", "Log into your account", "/login [password]", []);
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
			if(!$this->getLoader()->getAPI()->getAuthManager()->isPlayerRegistered($sender) or ($data = $this->getLoader()->getAPI()->getAuthManager()->getPlayer($sender)) === null){
				$sender->sendMessage(TextFormat::RED . "This account isn't registered!");

				return true;
			}
			if(count($args) !== 1){
				$sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());

				return true;
			}

			$password = implode(" ", $args);

			if($password === $data["password"]){
				$this->getLoader()->getAPI()->getAuthManager()->authenticatePlayer($sender);
				$sender->sendMessage("Logged in!");
				return true;
			}else{
				$sender->sendMessage(TextFormat::RED . "Incorrect password!");

				return true;
			}
		}else{
			$sender->sendMessage(TextFormat::RED . "This command only works in-game.");

			return true;
		}

	}
}
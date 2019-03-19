<?php

declare(strict_types=1);

namespace Adrenaline\Commands;

use Adrenaline\BaseFiles\BaseCommand;
use Adrenaline\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Server;

/**
 * Class GlobalMuteCommand
 *
 * @package Adrenaline\Commands
 */
class GlobalMuteCommand extends BaseCommand {
	/**
	 * GlobalMuteCommand constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "gmute", "Enable/disable globalmute", "/gmute [on|off]", []);
		$this->setPermission("adrenaline.command.gmute");
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
					case "off":
						$this->getLoader()->getAPI()->setGlobalMute(false);
						Server::getInstance()->broadcastMessage($this->getLoader()->getAPI()->getPrefix() . "GlobalMute disabled!");
						break;

					case "on":
						$this->getLoader()->getAPI()->setGlobalMute(true);
						Server::getInstance()->broadcastMessage($this->getLoader()->getAPI()->getPrefix() . "GlobalMute enabled!");
						break;
				}
			}
		}
		return false;
	}
}
<?php

declare(strict_types=1);

namespace Adrenaline\Commands;

use Adrenaline\BaseFiles\BaseCommand;
use Adrenaline\Loader;
use pocketmine\command\CommandSender;

/**
 * Class HostCommand
 *
 * @package Adrenaline\Commands
 */
class HostCommand extends BaseCommand {
	/**
	 * HostCommand constructor.
	 *
	 * @param Loader $loader
	 */
	public function __construct(Loader $loader){
		parent::__construct($loader, "host", "Host command!", "/host", []);
		$this->setPermission("adrenaline.command.host");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool|void
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		if($sender->hasPermission($this->getPermission())){
			if(isset($args[0])){
				switch($args[0]){
					case "start":
						if(!$this->plugin->getAPI()->isUsed()){
							$this->getLoader()->getAPI()->callTimer();
							$this->getLoader()->getAPI()->setUsed(true);
						}else{
							$sender->sendMessage("Task is already running!");
						}
						break;
				}
			}
		}else{
			$sender->sendMessage($this->sendNoPermission());
		}
	}
}
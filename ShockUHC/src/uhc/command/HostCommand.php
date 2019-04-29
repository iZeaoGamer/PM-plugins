<?php
declare(strict_types=1);

namespace uhc\command;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use uhc\Loader;

class HostCommand extends PluginCommand{
	private $plugin;
	public function __construct(Loader $plugin){
		parent::__construct("host", $plugin);
		$this->plugin = $plugin;
		$this->setPermission("shock.command.host");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		
		return true;
	}
}
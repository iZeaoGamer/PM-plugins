<?php
declare(strict_types=1);

namespace uhc\command;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use uhc\Loader;

class TpAllCommand extends PluginCommand{
	public function __construct(Loader $plugin){
		parent::__construct("tpall", $plugin);
		$this->setPermission("shock.command.tpall");
		$this->setUsage("/tpall");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}
		foreach($sender->getServer()->getOnlinePlayers() as $player){
			if($sender instanceof Player){
				$player->teleport($sender->getPosition());
			}
		}

		return true;
	}
}
<?php
declare(strict_types=1);

namespace network\commands;

use network\AvoltexPlayer;
use network\NetworkCore;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\Player;

class TransferServerCommand extends BaseCommand{
	public function __construct(NetworkCore $plugin){
		parent::__construct($plugin, "transferserver", "Transfer to another server!", "/transferserver [ip:port]", ["transfer"]);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(count($args) < 0){
			throw new InvalidCommandSyntaxException();
		}elseif(!$sender instanceof AvoltexPlayer){
			$sender->sendMessage("This command can only be executed as a player.");
			return false;
		}

		//TODO: Implement this.
		return true;
	}
}
<?php
declare(strict_types=1);

namespace Core\Commands;

use Core\CoreLoader;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class BalanceCommand extends BaseCommand{
	public function __construct(CoreLoader $loader){
		parent::__construct($loader, "balance", "Check your coin balance!", "balance", ["bal"]);
	}

	public function execute(CommandSender $sender, $commandLabel, array $args){
		if($sender instanceof Player){
			$sender->sendMessage("Balance: " . $this->getPlugin()->getEconomy()->getCoins($sender) . " coins");
		}
	}
}
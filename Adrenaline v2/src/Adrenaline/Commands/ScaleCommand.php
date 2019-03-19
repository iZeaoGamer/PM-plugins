<?php
declare(strict_types=1);

namespace Adrenaline\Commands;

use Adrenaline\BaseFiles\BaseCommand;
use Adrenaline\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class ScaleCommand extends BaseCommand{
	/**
	 * ScaleCommand constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "scale", "Set a player scale", "/scale [size]", []);
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		if($sender instanceof Player){
			if($sender->isOp()){
				$sender->setScale($args[0]);
			}
		}
	}
}
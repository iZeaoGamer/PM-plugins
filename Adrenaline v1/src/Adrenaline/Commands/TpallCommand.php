<?php
declare(strict_types=1);

namespace Adrenaline\Commands;

use Adrenaline\BaseFiles\BaseCommand;
use Adrenaline\Loader;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\Player;

/**
 * Class TpallCommand
 *
 * @package Adrenaline\Commands
 */
class TpallCommand extends BaseCommand {
	/**
	 * TpallCommand constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "tpall", "Teleport all players", "/tpall", []);
		$this->setPermission("adrenaline.command.tpall");
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
			if($sender instanceof Player){
				foreach($this->getLoader()->getServer()->getOnlinePlayers() as $p){
					$p->teleport(new Vector3($sender->x, $sender->y, $sender->z));
					$p->sendMessage("You have been teleported by " . $sender->getDisplayName());
				}
			}
		}
	}
}
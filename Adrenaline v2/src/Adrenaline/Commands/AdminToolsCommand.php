<?php
declare(strict_types=1);

namespace Adrenaline\Commands;

use Adrenaline\BaseFiles\BaseCommand;
use Adrenaline\Loader;
use pocketmine\command\CommandSender;

class AdminToolsCommand extends BaseCommand {
	/**
	 * AdminToolsCommand constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "admintools", "All tools for admins", "/admintools");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool|void
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){

	}
}
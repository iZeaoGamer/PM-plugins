<?php

declare(strict_types=1);

namespace Adrenaline\Commands;

use Adrenaline\BaseFiles\BaseCommand;
use Adrenaline\Loader;
use pocketmine\command\CommandSender;

/**
 * Class MeetupCommand
 *
 * @package Adrenaline\Commands
 */
class MeetupCommand extends BaseCommand {
	/**
	 * MeetupCommand constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "meetup", "Enables meetup core", "/meetup", []);
		$this->setPermission("adrenaline.command.meetup");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool|void
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		/*if($sender->hasPermission($this->getPermission())){
		    $plugin = $this->getLoader()->getServer()->getPluginManager()->getPlugin("AdrenMeetup");
		    $sender->getServer()->getPluginManager()->enablePlugin($plugin);
		    $sender->sendMessage($this->getLoader()->getPrefix() . "Enabling Meetup Core...");
		    $sender->getServer()->getPluginManager()->disablePlugin($this->getLoader());
		    $sender->sendMessage($this->getLoader()->getPrefix() . "Adrenaline Core shutting down...");
		}*/
	}
}
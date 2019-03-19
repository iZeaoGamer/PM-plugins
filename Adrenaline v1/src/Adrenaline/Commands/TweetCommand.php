<?php
declare(strict_types=1);

namespace Adrenaline\Commands;

use Adrenaline\BaseFiles\BaseCommand;
use Adrenaline\Loader;
use pocketmine\command\CommandSender;
use pocketmine\network\protocol\Info as ProtocolInfo;

/**
 * Class TweetCommand
 *
 * @package Adrenaline\Commands
 */
class TweetCommand extends BaseCommand {
	/**
	 * TweetCommand constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "tweet", "UHC AutoTweet", "/tweet [UHCType]", []);
		$this->setPermission("adrenaline.command.tweet");
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
			$this->getLoader()->getAPI()->getTwitterManager()->postTweet("AUTOTWEET\nHosting a " . strtoupper($args[0]) . " UHC\nIP: play.adrenalineuhc.net\nHost: " . $sender->getName() . "\nVersion: " . ProtocolInfo::MINECRAFT_VERSION . "\nDiscord: discord.gg/eJ7QGyS");
			$sender->sendMessage($this->getLoader()->getAPI()->getPrefix() . "Tweet sent");
			return true;
		}
		return false;
	}
}
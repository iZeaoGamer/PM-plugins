<?php
declare(strict_types=1);

namespace Core\Commands;

use Core\CoreLoader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;

class BaseCommand extends Command implements PluginIdentifiableCommand{

	public $owner;

	/**
	 * BaseCommand constructor.
	 *
	 * @param CoreLoader $loader
	 * @param string $name
	 * @param string $description
	 * @param null   $usageMessage
	 * @param array  $aliases
	 */
	public function __construct(CoreLoader $loader, $name, $description = "", $usageMessage = null, $aliases = []){
		parent::__construct($name, $description, $usageMessage, $aliases);
		$this->owner = $loader;
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		if($this->testPermission($sender)){
			$result = $this->onExecute($sender, $args);
			if(is_string($result)){
				$sender->sendMessage($result);
			}

			return true;
		}

		return false;
	}

	/**
	 * @param CommandSender $sender
	 * @param array         $args
	 */
	public function onExecute(CommandSender $sender, array $args){

	}

	/**
	 * @return CoreLoader|Plugin
	 */
	public function getPlugin() : Plugin{
		return $this->owner;
	}
}
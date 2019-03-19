<?php
declare(strict_types=1);

namespace network;

use avoltex\Core;
use network\commands\ProfileCommand;
use network\commands\ScaleCommand;
use network\commands\SetGroupCommand;
use network\commands\TransferServerCommand;
use network\utils\Ranks;
use network\tasks\CheckStatusTask;
use network\utils\User;
use pocketmine\network\mcpe\protocol\PacketPool;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class NetworkCore extends PluginBase{

	private static $instance;
	/** @var \mysqli */
	public $sql;

	public function onEnable(){
		$file = json_decode(file_get_contents($this->getData() . "sqldata.json"), true);
		$this->sql = new \mysqli($file["host"], $file["username"], $file["password"], "avoltex");

		self::$instance = $this;
		$this->registerFiles();
	}

	public function getData(){
		return $this->getServer()->getPluginPath() . "data" . DIRECTORY_SEPARATOR;
	}

	public static function getInstance(): NetworkCore{
		return self::$instance;
	}

	private function registerFiles(){
		new NetworkListener($this);
		$this->registerCommands();
	}

	public function getUser(): User{
		return new User();
	}

	private function registerCommands(){
		$map = $this->getServer()->getCommandMap();
		$map->getCommand("transferserver")->unregister($map);
		$map->getCommand("transferserver")->setLabel("transfer_disabled");
		$map->registerAll('network', [
			new SetGroupCommand($this),
			new TransferServerCommand($this)
		]);
	}
}
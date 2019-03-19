<?php
declare(strict_types=1);

namespace Core;

use Core\Commands\BalanceCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class CoreLoader extends PluginBase{

	private static $instance = null;
	public $hasSpawned = false;
	public $hasChanged = false;
	/** @var Config */
	public $getBanned;

	public function onEnable(){

		$this->saveResource("banned.json");
		$this->getBanned = new Config($this->getDataFolder()."banned.json", Config::JSON);

		$this->getServer()->getCommandMap()->register("avoltex", new BalanceCommand($this));

		static::$instance = $this;
	}

	/**
	 * @return API
	 */
	public function getAPI(){
		return new API($this);
	}
	/**
	 * @return CoreLoader
	 */
	public static function getInstance(){
		return static::$instance;
	}
}
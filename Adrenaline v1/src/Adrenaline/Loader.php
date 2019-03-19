<?php

/*
 * Once this is done, it'll probably be close to automatic
 * TODO list:
 * 1: Better security system
 * 2: Fix the timer
 * 3: Rewrite the horrible mess of a core
 * 4: Add scenario voting
 * 5: Add ranks so we can hopefully continue the server
 * 6: Possible TwitterAPI rewrite
 * 7: Add configurations for the owners to modify.
 *
 */
declare(strict_types=1);

namespace Adrenaline;

use Adrenaline\BaseFiles\API;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

/**
 * Class Loader
 *
 * @package Adrenaline
 */
class Loader extends PluginBase {

	private $api;
	public $permissions;

	const Android = 1;
	const iOS = 2;
	const OSX = 3;
	const FireOS = 4;
	const Gear_VR = 5;
	const Hololens = 6;
	const WINDOWS_10 = 7;

	public function onEnable(){
		if(!file_exists($this->getDataFolder())){
			mkdir($this->getDataFolder());
		}

		if(!file_exists($this->getDataFolder()."permissions.yml")){
			file_put_contents($this->getDataFolder() . "permissions.yml", $this->getResource("permissions.yml"));
		}

		$this->permissions = new Config($this->getDataFolder() . "permissions.yml", Config::YAML);
		$this->api = new API($this);

		$this->permissions->save();

		if($this->getAPI()->getMySQLDatabase()->connect_error){
			$this->getLogger()->error("Error connecting to MySQL database: " . $this->getAPI()->getMySQLDatabase()->connect_error);
		}else{
			$this->getLogger()->info("Successfully connected to the MySQL database");
		}

		/*$sql = "CREATE TABLE IF NOT EXISTS test (name VARCHAR(16))";

		if(mysqli_query($this->getAPI()->getMySQLDatabase(), $sql)){
			$this->getLogger()->info("Created table successfully.");
		}else{
			$this->getLogger()->alert("Failed to create table.");
		}*/
	}

	public function onDisable(){
		mysqli_close($this->getAPI()->getMySQLDatabase());
	}

	/**
	 * @return API
	 */
	public function getAPI() : API{
		return $this->api;
	}
}
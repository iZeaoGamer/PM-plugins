<?php
declare(strict_types=1);

namespace Core\Config;

use Core\CoreLoader;
use pocketmine\Player;
use pocketmine\utils\Config;

class ConfigLoader {

	private $loader;

	public function __construct(CoreLoader $loader){
		$this->loader = $loader;
		if(!file_exists($loader->getDataFolder())){
			mkdir($loader->getDataFolder());
		}elseif(!file_exists($loader->getDataFolder() . "players/")){
			mkdir($loader->getDataFolder() . "players/");
		}
	}

	/**
	 * @param Player $player
	 *
	 * @return array|null
	 */
	public function createPlayerData(Player $player){
		$name = strtolower($player->getName());
		if(!file_exists($this->loader->getDataFolder() . "players/$name.json")){
			$config = new Config($this->loader->getDataFolder() . "players/$name.json", Config::JSON);
			$config->set("coins", 0);
			$config->set("group", "guest");
			$config->save();

			return $config->getAll();
		}else{
			return null;
		}
	}

	/**
	 * @param Player $player
	 *
	 * @return bool|mixed
	 */
	public function isBanned(Player $player){
		return $this->loader->getBanned->get(strtolower($player->getName()));
	}

	/**
	 * @param Player $player
	 *
	 * @return array|null
	 */
	public function getPlayerData(Player $player){
		$name = strtolower($player->getName());
		if($name === ""){
			return null;
		}
		$path = $this->loader->getDataFolder() . "players/$name.json";
		if(!file_exists($path)){
			return null;
		}else{
			$config = new Config($path, Config::JSON);

			return $config->getAll();
		}
	}
}
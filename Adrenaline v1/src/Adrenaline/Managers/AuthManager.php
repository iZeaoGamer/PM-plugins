<?php
declare(strict_types=1);

namespace Adrenaline\Managers;

use Adrenaline\Listener\BasicAuthListener;
use Adrenaline\Loader;
use pocketmine\IPlayer;
use pocketmine\utils\Config;
use pocketmine\permission\PermissionAttachment;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

/**
 * Class AuthManager
 *
 * @package Adrenaline\Managers
 */
class AuthManager {

	/** @var PermissionAttachment[] */
	protected $needAuth = [];

	/** @var BasicAuthListener */
	protected $listener;

	private $plugin;

	/**
	 * AuthManager constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		$this->plugin = $plugin;
		$this->init();
	}

	/**
	 * @api
	 *
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function isPlayerAuthenticated(Player $player){
		return !isset($this->needAuth[spl_object_hash($player)]);
	}

	/**
	 * @api
	 *
	 * @param IPlayer $player
	 *
	 * @return bool
	 */
	public function isPlayerRegistered(IPlayer $player){
		$name = trim(strtolower($player->getName()));

		return file_exists($this->plugin->getDataFolder() . "players/$name.yml");
	}

	/**
	 * @api
	 *
	 * @param Player $player
	 *
	 * @return bool True if call not blocked
	 */
	public function authenticatePlayer(Player $player){
		if($this->isPlayerAuthenticated($player)){
			return true;
		}

		if(isset($this->needAuth[spl_object_hash($player)])){
			$attachment = $this->needAuth[spl_object_hash($player)];
			$player->removeAttachment($attachment);
			unset($this->needAuth[spl_object_hash($player)]);
		}
		$this->updatePlayer($player, $player->getAddress());

		return true;
	}

	/**
	 * @api
	 *
	 * @param Player $player
	 *
	 * @return bool True if call not blocked
	 */
	public function deauthenticatePlayer(Player $player){
		if(!$this->isPlayerAuthenticated($player)){
			return true;
		}

		$attachment = $player->addAttachment($this->plugin);
		$this->needAuth[spl_object_hash($player)] = $attachment;

		$config = $this->getPlayer($player);
		if($config === null){
			$player->sendMessage(TextFormat::GOLD . "You must register to play! /register [password]");
		}else{
			$player->sendMessage(TextFormat::YELLOW . "Login to your account, by using /login [password]");
		}

		return true;
	}

	/**
	 * @api
	 *
	 * @param IPlayer $player
	 * @param string  $password
	 *
	 * @return bool
	 */
	public function registerPlayer(IPlayer $player, $password){
		if(!$this->isPlayerRegistered($player)){
			$this->registerThePlayer($player, $password);
			return true;
		}
		return false;
	}

	/**
	 * @param IPlayer $player
	 * @param         $hash
	 *
	 * @return array
	 */
	public function registerThePlayer(IPlayer $player, $hash){
		$name = trim(strtolower($player->getName()));
		if(!file_exists($this->plugin->getDataFolder() . "players/")){
			mkdir($this->plugin->getDataFolder() . "players/");
		}
		$data = new Config($this->plugin->getDataFolder() . "players/$name.yml", Config::YAML);
		$data->set("ip", null);
		$data->set("password", $hash);
		$data->set("group", "default");
		$data->save();

		return $data->getAll();
	}

	/**
	 * @param Player $player
	 */
	public function closePlayer(Player $player){
		unset($this->needAuth[spl_object_hash($player)]);
	}


	public function init(){
		if(!file_exists($this->plugin->getDataFolder() . "players/")){
			mkdir($this->plugin->getDataFolder() . "players/");
		}

		foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
			$this->deauthenticatePlayer($player);
		}

	}

	/**
	 * @param Player $player
	 *
	 * @return array|null
	 */
	public function getPlayer(Player $player){
		$name = trim(strtolower($player->getName()));
		if($name === ""){
			return null;
		}
		$path = $this->plugin->getDataFolder() . "players/$name.yml";
		if(!file_exists($path)){
			return null;
		}else{
			$config = new Config($path, Config::YAML);
			return $config->getAll();
		}
	}


	/**
	 * @param IPlayer $player
	 * @param array   $config
	 */
	public function savePlayer(IPlayer $player, array $config){
		$name = trim(strtolower($player->getName()));
		$data = new Config($this->plugin->getDataFolder() . "players/$name.yml", Config::YAML);
		$data->setAll($config);
		$data->save();
	}

	/**
	 * @param Player $player
	 * @param null   $lastIP
	 */
	public function updatePlayer(Player $player, $lastIP = null){
		$data = $this->getPlayer($player);
		if($data !== null){
			if($lastIP !== null){
				$data["ip"] = $lastIP;
			}
			$this->savePlayer($player, $data);
		}
	}

}

<?php
declare(strict_types=1);

namespace Adrenaline\BaseFiles;

use Adrenaline\Listener\BasicAuthListener;
use Adrenaline\Listener\PlayerListener;
use Adrenaline\Listener\ScenarioListener;
use Adrenaline\Loader;
use Adrenaline\Managers\AuthManager;
use Adrenaline\Managers\CommandManager;
use Adrenaline\Managers\ScenarioManager;
use Adrenaline\Tasks\TimerTask;
use Adrenaline\TwitterAPI\TwitterManager;
use pocketmine\network\protocol\SetDifficultyPacket;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

/**
 * Class API
 *
 * @package Adrenaline\BaseFiles
 */
class API {

	private $database;
	private $gmute = false;
	private $used = false;
	/** @var Loader */
	private $loader;
	/** @var TwitterManager */
	private $twittermanager;
	/** @var TimerTask */
	private $timer;
	/** @var ScenarioManager */
	private $scenarioManager;
	/** @var PlayerListener */
	private $playerListener;
	/** @var AuthManager */
	private $authManager;
	/** @var ScenarioListener */
	private $scenarioListener;
	/** @var CommandManager */
	private $commandManager;
	/** @var BasicAuthListener */
	private $authListener;

	/**
	 * API constructor.
	 *
	 * @param Loader $loader
	 */
	public function __construct(Loader $loader){
		$this->loader = $loader;
		$this->init();
	}

	/**
	 * @return \mysqli
	 */
	public function getMySQLDatabase(){
		$host = '192.99.245.17';
		$user = '1238';
		$password = "1315149f6d";
		$db = '1238';
		$port = 3306;

		return $this->database = new \mysqli($host, $user, $password, $db, $port);
	}


	/**
	 * @return array
	 */
	public function getAvaliableGroups() : array{
		//Works fine enough right now, TODO: Remove case sensitivity
		return ["default", "trialmod", "mod", "owner", "famous", "famous+", "legend", "legend+"];
	}

	public function getGroup(Player $player){
		$data = new Config($this->getLoader()->getDataFolder() . "players/" . $player->getName() . ".yml", Config::YAML);
		$group = $data->get("group");
		if($group == null){
			return null;
		}else{
			return $group;
		}
	}

	public function addPermission(Player $player, $perm){
		$player->addAttachment($this->getLoader(), $perm, true);
	}

	/**
	 * @return array
	 */
	public function getUHCTypes() : array{
		return ["ffa", "to2", "to3", "to4", "to5"];
	}

	/**
	 * @return Loader
	 */
	private function getLoader(){
		return $this->loader;
	}

	public function init(){
		$this->authListener = new BasicAuthListener($this->getLoader());
		$this->authManager = new AuthManager($this->getLoader());
		$this->commandManager = new CommandManager($this->getLoader());
		$this->playerListener = new PlayerListener($this->getLoader());
		$this->scenarioListener = new ScenarioListener($this->getLoader());
		$this->scenarioManager = new ScenarioManager($this->getLoader());
		$this->timer = new TimerTask($this->getLoader());
		$this->twittermanager = new TwitterManager($this->getLoader());
	}

	/**
	 * @return BasicAuthListener
	 */
	public function getAuthListener() : BasicAuthListener{
		return $this->authListener;
	}

	/**
	 * @return AuthManager
	 */
	public function getAuthManager() : AuthManager{
		return $this->authManager;
	}

	/**
	 * @return CommandManager
	 */
	public function getCommandManager() : CommandManager{
		return $this->commandManager;
	}

	/**
	 * @return PlayerListener
	 */
	public function getPlayerListener() : PlayerListener{
		return $this->playerListener;
	}

	/**
	 * @return ScenarioListener
	 */
	public function getScenarioListener() : ScenarioListener{
		return $this->scenarioListener;
	}

	/**
	 * @return ScenarioManager
	 */
	public function getScenarioManager() : ScenarioManager{
		return $this->scenarioManager;
	}

	/**
	 * @return TimerTask
	 */
	public function getTimer() : TimerTask{
		return $this->timer;
	}

	/**
	 * @return TwitterManager
	 */
	public function getTwitterManager() : TwitterManager{
		return $this->twittermanager;
	}

	/**
	 * @return string
	 */
	public function getPrefix(){
		return TextFormat::BOLD . TextFormat::RED . "Adrenaline> " . TextFormat::RESET . TextFormat::GOLD;
	}

	public function callTimer(){
		$main = new TimerTask($this->getLoader());

		$this->getLoader()->getServer()->getScheduler()->scheduleRepeatingTask($main, 20)->getTaskId();
	}

	/**
	 * @return bool
	 */
	public function isUsed(){
		return $this->used;
	}

	/**
	 * @param bool $used
	 */
	public function setUsed($used = false){
		$this->used = $used;
	}

	/**
	 * @return bool
	 */
	public function getGlobalMute(){
		return $this->gmute;
	}

	/**
	 * @param bool $value
	 */
	public function setGlobalMute($value = false){
		$this->gmute = $value;
	}

	/**
	 * @param int $difficulty
	 */
	public function setDifficulty($difficulty = 0){
		$this->getLoader()->getServer()->setConfigInt("difficulty", $difficulty);
		$pk = new SetDifficultyPacket();
		$pk->difficulty = $difficulty;
		$this->getLoader()->getServer()->broadcastPacket($this->getLoader()->getServer()->getOnlinePlayers(), $pk);
	}
}
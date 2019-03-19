<?php
declare(strict_types=1);

namespace Adrenaline\BaseFiles;

use Adrenaline\Listener\PlayerListener;
use Adrenaline\Loader;
use Adrenaline\Managers\CommandManager;
use Adrenaline\Managers\ScenarioManager;
use Adrenaline\Tasks\TimerTask;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\network\protocol\AddPlayerPacket;
use pocketmine\network\protocol\BossEventPacket;
use pocketmine\network\protocol\SetDifficultyPacket;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\utils\UUID;

class API {

	private $config, $chatconfig;
	private $gmute = false;
	private $used = false;
	/** @var Loader */
	private $loader;
	/** @var TimerTask */
	private $timer;
	/** @var ScenarioManager */
	private $scenarioManager;
	/** @var PlayerListener */
	private $playerListener;
	/** @var CommandManager */
	private $commandManager;

	/**
	 * API constructor.
	 *
	 * @since 1.0.0 Beta 1
	 *
	 * @param Loader $loader
	 */
	public function __construct(Loader $loader){
		$this->loader = $loader;
		$this->init();
	}

	/**
	 * @since 1.0.0 Beta 1
	 */
	public function init(){
		$this->commandManager = new CommandManager($this->getLoader());
		$this->playerListener = new PlayerListener($this->getLoader());
		$this->scenarioManager = new ScenarioManager($this->getLoader());
		$this->timer = new TimerTask($this->getLoader());
		$this->config = new Config($this->getLoader()->getDataFolder() . "config.json", Config::JSON);
		$this->chatconfig = new Config($this->getLoader()->getDataFolder() . "chat.json", Config::JSON);
	}

	/**
	 * @since 1.0.0 Beta 1
	 *
	 * @return Loader
	 */
	private function getLoader() : Loader{
		return $this->loader;
	}

	/**
	 * @since 1.0.0 Beta 2
	 *
	 * @return CommandManager
	 */
	public function getCommandManager() : CommandManager{
		return $this->commandManager;
	}

	/**
	 * @since 1.0.0 Beta 1
	 *
	 * @return PlayerListener
	 */
	public function getPlayerListener() : PlayerListener{
		return $this->playerListener;
	}

	/**
	 * @since 1.0.0 Beta 4
	 *
	 * @return ScenarioManager
	 */
	public function getScenarioManager() : ScenarioManager{
		return $this->scenarioManager;
	}

	/**
	 * @since 1.0.0 Beta 3
	 *
	 * @return TimerTask
	 */
	public function getTimer() : TimerTask{
		return $this->timer;
	}

	/**
	 * @since 1.0.0 Beta 1
	 *
	 * @return string
	 */
	public function getPrefix() : string{
		return TextFormat::BOLD . TextFormat::RED . "Adrenaline> " . TextFormat::RESET . TextFormat::GOLD;
	}

	/**
	 * @param $resource
	 * @param $value
	 */
	public function setInMainConfig($resource, $value){
		//TODO
	}

	/**
	 * @since 1.0.1
	 *
	 * @param $command
	 *
	 * @return mixed
	 */
	public function isCommandDisabled($command){
		return $this->getMainConfig()->get("commands")[$command];
	}

	/**
	 * @since 1.0.1
	 *
	 * @return Config
	 */
	public function getMainConfig() : Config{
		return $this->config;
	}

	/**
	 * @since 1.0.0 Beta 3
	 *
	 * @return int
	 */
	public function callTimer() : int{
		$main = new TimerTask($this->getLoader());

		return $this->getLoader()->getServer()->getScheduler()->scheduleRepeatingTask($main, 20)->getTaskId();
	}

	/**
	 * @since 1.0.0 Beta 2
	 *
	 * @return bool
	 */
	public function isUsed() : bool{
		return $this->used;
	}

	/**
	 * @since 1.0.0 Beta 2
	 *
	 * @param bool $used
	 */
	public function setUsed(bool $used = false){
		$this->used = $used;
	}

	/**
	 * @since 1.0.0 Beta 2
	 *
	 * @return bool
	 */
	public function getGlobalMute() : bool{
		return $this->gmute;
	}

	/**
	 * @since 1.0.0 Beta 2
	 *
	 * @param bool $value
	 */
	public function setGlobalMute(bool $value = false){
		$this->gmute = $value;
	}

	/**
	 * @since 1.0.0 Beta 4
	 *
	 * @param int $difficulty
	 */
	public function setDifficulty(int $difficulty = 0){
		$this->getLoader()->getServer()->setConfigInt("difficulty", $difficulty);
		$pk = new SetDifficultyPacket();
		$pk->difficulty = $difficulty;
		$this->getLoader()->getServer()->broadcastPacket($this->getLoader()->getServer()->getOnlinePlayers(), $pk);
	}

	/**
	 * @since 1.0.1
	 *
	 * @return array
	 */
	public function getAvaliableGroups() : array{
		return ["default", "mod", "owner", "famous", "famous+", "legend", "legend+"];
	}

	/**
	 * @since 1.0.1
	 *
	 * @param Player $player
	 * @param string $message
	 *
	 * @return bool|mixed
	 */
	public function getChatFormat(Player $player, string $message){
		$group = $this->getGroup($player);
		$format = $this->getChatConfig()->get($group);
		$format = $format['format'];
		$format = str_replace("{name}", $player->getName(), $format);
		$format = str_replace("{message}", $message, $format);

		return $format;
	}

	/**
	 * @since 1.0.1
	 *
	 * @param Player $player
	 *
	 * @return bool|mixed
	 */
	public function getGroup(Player $player){
		$name = trim(strtolower($player->getName()));
		$data = new Config($this->getLoader()->getDataFolder() . "players/$name.yml", Config::YAML);

		$group = $data->get("group");

		return $group;
	}

	/**
	 * @since 1.0.1
	 *
	 * @return Config
	 */
	public function getChatConfig() : Config{
		return $this->chatconfig;
	}

	/**
	 * @param Player $player
	 * @param array  $config
	 */
	public function savePlayerData(Player $player, array $config){
		$name = trim(strtolower($player->getName()));
		$data = new Config($this->getLoader()->getDataFolder() . "players/$name.yml", Config::YAML);
		$data->setAll($config);
		$data->save();
	}

	/**
	 * @param Player $player
	 *
	 * @return array
	 */
	public function createPlayerData(Player $player){
		$name = trim(strtolower($player->getName()));
		$path = $this->getLoader()->getDataFolder() . "players/$name.yml";
		if(!file_exists($path)){
			$data = new Config($this->getLoader()->getDataFolder() . "players/$name.yml", Config::YAML);
			$data->set("group", "default");
			$data->save();

			return $data->getAll();
		}else{
			return null;
		}
	}

	/**
	 * @param Player $player
	 *
	 * @return array|null
	 */
	public function getPlayerData(Player $player){
		$name = trim(strtolower($player->getName()));
		if($name === ""){
			return null;
		}
		$path = $this->getLoader()->getDataFolder() . "players/$name.yml";
		if(!file_exists($path)){
			return null;
		}else{
			$config = new Config($path, Config::YAML);

			return $config->getAll();
		}
	}

	/**
	 * @since 1.0.1
	 */
	//TODO: Make this look better, and cleanup.

	public function sendBossBar(){
		foreach($this->getLoader()->getServer()->getOnlinePlayers() as $p){
			$flags = 1 << Entity::DATA_FLAG_INVISIBLE;
			$flags |= 0 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG;
			$flags |= 0 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG;
			$flags |= 1 << Entity::DATA_FLAG_IMMOBILE;

			$pk = new BossEventPacket();
			$pk->eid = 8385757857;
			$this->getLoader()->getServer()->broadcastPacket($this->getLoader()->getServer()->getOnlinePlayers(), $pk);

			$spawn4 = new AddPlayerPacket();
			$spawn4->eid = 8385757857;
			$spawn4->uuid = UUID::fromRandom();
			$spawn4->username = '';
			$spawn4->x = $p->getX();
			$spawn4->y = $p->getY() - 10;
			$spawn4->z = $p->getZ();
			$spawn4->speedX = 0;
			$spawn4->speedY = 0;
			$spawn4->speedZ = 0;
			$spawn4->yaw = 0;
			$spawn4->pitch = 0;
			$spawn4->item = Item::get(0);
			$spawn4->metadata = [
				Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
				Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, "\n\n    Adrenaline\nX: " . round($p->getX()) . " Y: " . round($p->getY()) . " Z: " . round($p->getZ()) . "\nHealth: " . $p->getHealth() / 2],
				Entity::DATA_LEAD_HOLDER_EID => [Entity::DATA_TYPE_LONG, -1],
			];
			$p->dataPacket($spawn4);
		}
	}
}
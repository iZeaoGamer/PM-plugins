<?php
declare(strict_types=1);

namespace device;

use _64FF00\PureChat\PureChat;
use _64FF00\PurePerms\event\PPGroupChangedEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\IPlayer;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase implements Listener{

	private $device;

	public function onEnable(){
		if(!$this->getServer()->getPluginManager()->getPlugin("PureChat")){
			$this->getLogger()->notice("DeviceNametag requires PureChat to work!");
			$this->getServer()->getPluginManager()->disablePlugin($this);
		}
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
	}

	private function setDevice(int $device){
		$this->device = $device;
	}

	public function getDeviceOS() : int{
		return $this->device;
	}

	public function convertOStoString(int $input){
		$config = $this->getConfig()->getAll();
		switch($input){
			case 1:
				return $config["devices"]["Android"];
			case 2:
				return $config["devices"]["iOS"];
			case 3:
				return $config["devices"]["OSX"];
			case 4:
				return $config["devices"]["FireOS"];
			case 5:
				return $config["devices"]["GearVR"];
			case 6:
				return $config["devices"]["Hololens"];
			case 7:
				return $config["devices"]["Windows10"];
		}
	}

	public function handleDataReceive(DataPacketReceiveEvent $ev){
		$packet = $ev->getPacket();
		if($packet instanceof LoginPacket){
			$this->setDevice($packet->clientData["DeviceOS"]);
		}
	}

	public function handleJoin(PlayerJoinEvent $ev){
		$player = $ev->getPlayer();

		//TODO: Remove this workaround.
		$this->getServer()->getScheduler()->scheduleDelayedTask(new Workaround($this, $player), 1);
	}

	public function handleGroupChat(PPGroupChangedEvent $ev){
		/** @var IPlayer $player */
		$player = $ev->getPlayer();
		if($player instanceof Player){
			//TODO: Remove this workaround.
			$this->getServer()->getScheduler()->scheduleDelayedTask(new Workaround($this, $player), 1);
		}
	}
}
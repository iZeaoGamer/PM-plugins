<?php
declare(strict_types=1);

namespace Core;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;

class EventListener implements Listener{

	private $loader;

	public function __construct(CoreLoader $loader){
		$loader->getServer()->getPluginManager()->registerEvents($this, $loader);
		$this->loader = $loader;
	}

	public function handlePreLogin(PlayerPreLoginEvent $event){
		$player = $event->getPlayer();
		$this->loader->getAPI()->getServerConfig()->createPlayerData($player);
		if($this->loader->getAPI()->getServerConfig()->isBanned($player)){
			$banned = $this->loader->getAPI()->getServerConfig()->isBanned($player);
			$reason = "Reason: " . $banned["reason"] . "\nBanned by: ".$banned["staff"]."\nDate: " . $banned["date"];
			$player->close("banned", $reason);
		}
	}
}
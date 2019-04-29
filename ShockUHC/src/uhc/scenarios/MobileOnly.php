<?php

namespace uhc\scenarios;

use network\NetworkPlayer;
use pocketmine\Player;
use uhc\Loader;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\TextFormat;

class MobileOnly extends Scenario{
	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		parent::__construct($plugin, "MobileOnly", ["mo"]);
		$this->plugin = $plugin;
	}

	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		if($this->isActive()){
			if($player instanceof NetworkPlayer){
				if(!$player->hasPermission("shock.bypass")){
					if(!in_array($player->getDeviceOS(), [1, 2, 4])){
						$player->close();
					}
				}
			}
		}
	}
}
<?php

namespace uhc\scenarios;

use uhc\Loader;

use pocketmine\event\player\PlayerJoinEvent;

class DoubleHealth extends Scenario{

	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		parent::__construct($plugin, "DoubleHealth", ["dh"]);
		$this->plugin = $plugin;
	}

	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		if($this->isActive()){
			if($player->hasPlayedBefore() === false){
				$player->setMaxHealth(40);
				$player->setHealth(40);
			}else{
				$player->setHealth($player->getHealth());
				$player->setMaxHealth(40);
			}
		}
	}
}
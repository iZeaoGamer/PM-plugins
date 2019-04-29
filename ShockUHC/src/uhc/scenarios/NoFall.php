<?php

namespace uhc\scenarios;

use pocketmine\Player;
use uhc\Loader;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;

class NoFall extends Scenario{
	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		parent::__construct($plugin, "NoFall", ["nf"]);
		$this->plugin = $plugin;
	}

	public function onDamage(EntityDamageEvent $event){
		if($this->isActive()){
			if($event->getEntity() instanceof Player){
				if($event->getCause() === EntityDamageEvent::CAUSE_FALL){
					$event->setCancelled();
				}
			}
		}
	}
}
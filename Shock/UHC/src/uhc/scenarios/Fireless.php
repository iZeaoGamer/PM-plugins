<?php

namespace uhc\scenarios;

use pocketmine\Player;
use uhc\Loader;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;

class Fireless extends Scenario{
	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		parent::__construct($plugin, "Fireless", ["fl"]);
		$this->plugin = $plugin;
	}

	public function onDamage(EntityDamageEvent $event){
		if($this->isActive()){
			$entity = $event->getEntity();
			$cause = $event->getCause();
			if($entity instanceof Player){
				if($cause === EntityDamageEvent::CAUSE_FIRE || $cause === EntityDamageEvent::CAUSE_FIRE_TICK || $cause === EntityDamageEvent::CAUSE_LAVA){
					$event->setCancelled();
					$entity->extinguish();
				}
			}
		}
	}
}
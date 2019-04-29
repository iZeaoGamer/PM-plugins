<?php

namespace uhc\scenarios;

use pocketmine\math\Vector3;
use uhc\Loader;
use pocketmine\event\entity\EntityDamageEvent;
use uhc\UHCPlayer;

class Switcheroo extends Scenario{

	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		parent::__construct($plugin, "Switcheroo", ["sr"]);
		$this->plugin = $plugin;
	}

	public function handleEntityDamage(EntityDamageEvent $event){
		if($this->isActive()){
			$entity = $event->getEntity();
			$cause = $event->getCause();
			if($cause === EntityDamageEvent::CAUSE_PROJECTILE){
				/** @var UHCPlayer $damager */
				$damager = $event->getDamager();
				$damagerCoords = new Vector3($damager->getX(), $damager->getY(), $damager->getZ());
				$entityCoords = new Vector3($entity->getX(), $entity->getY(), $damager->getZ());
				$damager->teleport($damagerCoords);
				$entity->teleport($entityCoords);
			}
		}
	}
}
	
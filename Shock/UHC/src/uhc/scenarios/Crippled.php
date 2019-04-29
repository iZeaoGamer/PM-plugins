<?php

namespace uhc\scenarios;

use uhc\Loader;
use pocketmine\Player;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\entity\EntityDamageEvent;

class Crippled extends Scenario{

	private $plugin;

	public function __construct(Loader $plugin){
		parent::__construct($plugin, "Crippled", ["cp"]);
		$this->plugin = $plugin;
	}

	public function onDamage(EntityDamageEvent $event){
		$entity = $event->getEntity();
		if($this->isActive()){
			if($entity instanceof Player){
				if($event->getCause() === EntityDamageEvent::CAUSE_FALL){
					$entity->addEffect(new EffectInstance(Effect::getEffect(Effect::SLOWNESS), 300, 0, false));
				}
			}
		}
	}
}
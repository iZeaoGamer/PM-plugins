<?php

namespace uhc\scenarios;

use uhc\Loader;
use uhc\scenarios\tasks\NoCleanTask;
use pocketmine\utils\TextFormat;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerDeathEvent;
use uhc\UHCPlayer;

class NoClean extends Scenario{
	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		parent::__construct($plugin, "NoClean", ["nc"]);
		$this->plugin = $plugin;
	}

	public function onDamage(EntityDamageEvent $event){
		if($this->isActive()){
			$entity = $event->getEntity();
			if($event instanceof EntityDamageByEntityEvent){
				$damager = $event->getDamager();
				if(!$entity instanceof UHCPlayer || !$damager instanceof UHCPlayer) return;
				if($entity->hasNoClean()){
					$damager->sendMessage(TextFormat::RED . "[NoClean] " . $entity->getName() . " has an invincibility timer!");
					$event->setCancelled();
				}else{
					if($damager->hasNoClean()){
						$damager->setNoCleanTime(0);
					}
				}
			}
		}
	}

	public function onDeath(PlayerDeathEvent $event){
		if($this->isActive()){
			$player = $event->getPlayer();
			$cause = $player->getLastDamageCause();
			if($cause instanceof EntityDamageByEntityEvent){
				$killer = $cause->getDamager();
				if($killer instanceof UHCPlayer){
					$killer->sendMessage(TextFormat::GREEN . "[NoClean] You have a 20 second invincibility timer!");
					new NoCleanTask($this->plugin, $killer);
				}
			}
		}
	}
}
              
              
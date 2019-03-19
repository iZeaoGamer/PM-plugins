<?php

declare(strict_types=1);

namespace Adrenaline\Listener;

use Adrenaline\Loader;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\server\DataPacketReceiveEvent;

/**
 * Class ScenarioListener
 *
 * @package Adrenaline\Listener
 */
class ScenarioListener implements Listener {

	public $plugin;

	/**
	 * ScenarioListener constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		$this->plugin = $plugin;
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	/**
	 * @param PlayerDeathEvent $event
	 */
	public function onDeath(PlayerDeathEvent $event){
		$this->plugin->getAPI()->getScenarioManager()->handleDeath($event);
	}

	/**
	 * @param BlockBreakEvent $event
	 */
	public function onBreak(BlockBreakEvent $event){
		$this->plugin->getAPI()->getScenarioManager()->handleBreak($event);
	}

	/**
	 * @param EntityDamageEvent $event
	 */
	public function onDamage(EntityDamageEvent $event){
		$this->plugin->getAPI()->getScenarioManager()->handleDamage($event);
	}

	/**
	 * @param EntityDeathEvent $event
	 */
	public function onEntityDeath(EntityDeathEvent $event){
		$this->plugin->getAPI()->getScenarioManager()->handleEntityDeath($event);
	}

	/**
	 * @param DataPacketReceiveEvent $event
	 */
	public function onDataRecieve(DataPacketReceiveEvent $event){
		$this->plugin->getAPI()->getScenarioManager()->handleDataRecieve($event);
	}
}
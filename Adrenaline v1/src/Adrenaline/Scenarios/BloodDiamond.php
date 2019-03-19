<?php
declare(strict_types=1);

namespace Adrenaline\Scenarios;

use Adrenaline\BaseFiles\Scenario;
use Adrenaline\Loader;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageEvent;

/**
 * Class BloodDiamond
 *
 * @package Adrenaline\Scenarios
 */
class BloodDiamond extends Scenario {
	/**
	 * BloodDiamond constructor.
	 *
	 * @param Loader $loader
	 */
	public function __construct(Loader $loader){
		parent::__construct($loader, "BloodDiamond", ["bd"]);
	}

	/**
	 * @param BlockBreakEvent $event
	 *
	 * @return mixed|void
	 */
	public function onBreak(BlockBreakEvent $event){
		switch($event->getBlock()->getId()){
			case Block::DIAMOND_ORE:
				$event->getPlayer()->attack(1, new EntityDamageEvent($event->getPlayer(), EntityDamageEvent::CAUSE_CUSTOM, 1));
		}
	}
}
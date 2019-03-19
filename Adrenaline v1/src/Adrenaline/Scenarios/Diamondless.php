<?php
declare(strict_types=1);

namespace Adrenaline\Scenarios;

use Adrenaline\BaseFiles\Scenario;
use Adrenaline\Loader;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;

/**
 * Class Diamondless
 *
 * @package Adrenaline\Scenarios
 */
class Diamondless extends Scenario {
	/**
	 * Diamondless constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "Diamondless", ["dl"]);
	}

	/**
	 * @param BlockBreakEvent $event
	 *
	 * @return mixed|void
	 */
	public function onBreak(BlockBreakEvent $event){
		switch($event->getBlock()->getId()){
			case Block::DIAMOND_ORE:
				$event->setDrops([]);
				break;
		}
	}
}
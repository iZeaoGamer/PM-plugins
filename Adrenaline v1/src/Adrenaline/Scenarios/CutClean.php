<?php

declare(strict_types=1);

namespace Adrenaline\Scenarios;

use Adrenaline\BaseFiles\Scenario;
use Adrenaline\Loader;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\Item;

/**
 * Class CutClean
 *
 * @package Adrenaline\Scenarios
 */
class CutClean extends Scenario {

	public $plugin;

	/**
	 * CutClean constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "CutClean", ["cc"]);
	}

	/**
	 * @param BlockBreakEvent $event
	 *
	 * @return mixed|void
	 */
	public function onBreak(BlockBreakEvent $event){
		switch($event->getBlock()->getId()){
			case Block::IRON_ORE:
				$event->setDrops([Item::get(Item::IRON_INGOT)]);
				break;
			case Block::GOLD_ORE:
				$event->setDrops([Item::get(Item::GOLD_INGOT)]);
				break;
		}
	}
}
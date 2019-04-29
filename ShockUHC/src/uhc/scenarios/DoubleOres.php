<?php

namespace uhc\scenarios;

use uhc\Loader;

use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;

class DoubleOres extends Scenario{
	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		parent::__construct($plugin, "DoubleOres", ["do"]);
		$this->plugin = $plugin;
	}

	public function onBreak(BlockBreakEvent $event){
		if($this->isActive()){
			switch($event->getBlock()->getId()){
				case Block::IRON_ORE:
				case Block::GOLD_ORE:
				case Block::DIAMOND_ORE:
					foreach($event->getDrops() as $item){
						$event->setDrops([Item::get($item->getId(), 0, 2)]);
					}
					break;
			}
		}
	}
}
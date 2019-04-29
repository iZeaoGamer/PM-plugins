<?php

namespace uhc\scenarios;

use uhc\Loader;

use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;

class DoubleOrNothing extends Scenario{
	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		parent::__construct($plugin, "DoubleOrNothing", ["don"]);
		$this->plugin = $plugin;
	}

	public function onBreak(BlockBreakEvent $event){
		if($this->isActive()){
			switch($event->getBlock()->getId()){
				case Block::IRON_ORE:
					switch(mt_rand(1, 2)){
						case 1:
							$event->setDrops([Item::get(Item::IRON_INGOT, 0, 2)]);
							break;
						case 2:
							$event->setDrops([Item::get(Item::AIR)]);
							break;
					}
					break;
				case Block::GOLD_ORE:
					switch(mt_rand(1, 2)){
						case 1:
							$event->setDrops([Item::get(Item::GOLD_INGOT, 0, 2)]);
							break;
						case 2:
							$event->setDrops([Item::get(Item::AIR)]);
							break;
					}
					break;
				case Block::DIAMOND_ORE:
					switch(mt_rand(1, 2)){
						case 1:
							$event->setDrops([Item::get(Item::DIAMOND, 0, 2)]);
							break;
						case 2:
							$event->setDrops([Item::get(Item::AIR)]);
							break;
					}
					break;
			}
		}
	}
}
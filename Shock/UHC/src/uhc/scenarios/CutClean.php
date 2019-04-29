<?php

namespace uhc\scenarios;

use uhc\Loader;

use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;

class CutClean extends Scenario{
	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		parent::__construct($plugin, "CutClean", ["cc"]);
		$this->plugin = $plugin;
	}

	public function onBreak(BlockBreakEvent $event){
		if($this->isActive()){
			$hand = $event->getPlayer()->getInventory()->getItemInHand();
			switch($event->getBlock()->getId()){
				case Block::IRON_ORE:
					$event->setDrops([Item::get(Item::IRON_INGOT)]);
					break;
				case Block::GOLD_ORE:
					$event->setDrops([Item::get(Item::GOLD_INGOT)]);
					break;
				case Block::LEAVES:
					if($hand->getId() === Item::SHEARS){
						if(mt_rand(0, 2) === 1) $event->setDrops([Item::get(Item::APPLE)]);
					}else{
						if(mt_rand(0, 5) === 3) $event->setDrops([Item::get(Item::APPLE)]);
					}
			}
		}
	}
}
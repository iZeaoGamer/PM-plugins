<?php

namespace uhc\scenarios;

use uhc\Loader;

use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;

class BloodDiamond extends Scenario{
	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		parent::__construct($plugin, "BloodDiamond", ["bd"]);
		$this->plugin = $plugin;
	}

	public function onBreak(BlockBreakEvent $event){
		if($this->isActive()){
			switch($event->getBlock()->getId()){
				case Block::DIAMOND_ORE:
					$event->getPlayer()->setHealth($event->getPlayer()->getHealth() - 1);
					break;
			}
		}
	}
}
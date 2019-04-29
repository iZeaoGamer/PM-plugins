<?php

namespace uhc\scenarios;

use uhc\Loader;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\utils\TextFormat;

class Barebones extends Scenario{
	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		parent::__construct($plugin, "Barebones", ["bb"]);
		$this->plugin = $plugin;
	}

	public function onCraft(CraftItemEvent $event){
		if($this->isActive()){
			$items = $event->getOutputs();
			foreach($items as $item){
				if($item->getId() === Item::GOLDEN_APPLE){
					$event->getPlayer()->sendMessage(TextFormat::RED . " You cannot craft this item in barebones scenario!");
					$event->setCancelled(true);
				}
			}
		}
	}

	public function onDeath(PlayerDeathEvent $event){
		if($this->isActive()){
			$event->setDrops(
				[
					Item::get(Item::DIAMOND, 0, 1),
					Item::get(Item::GOLDEN_APPLE, 0, 1),
					Item::get(Item::ARROW, 0, 32),
					Item::get(Item::STRING, 0, 2)
				]);
		}
	}

	public function onBreak(BlockBreakEvent $event){
		if($this->isActive()){
			switch($event->getBlock()->getId()){
				case Block::DIAMOND_ORE:
				case Block::GOLD_ORE:
					$event->setDrops([Item::get(Item::IRON_INGOT)]);
					break;
			}
		}
	}
}
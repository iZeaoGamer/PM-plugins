<?php

namespace uhc\scenarios;

use uhc\Loader;
use pocketmine\item\Item;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\utils\TextFormat;

class EnchantedDeath extends Scenario{

	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		parent::__construct($plugin, "EnchantedDeath", ["ed"]);
		$this->plugin = $plugin;
	}

	public function onCraft(CraftItemEvent $event){
		if($this->isActive()){
			$items = $event->getOutputs();
			foreach($items as $item){
				if($item->getId() === Item::ENCHANTING_TABLE){
					$event->getPlayer()->sendMessage(TextFormat::RED . "You cannot craft this item in enchanted death scenario!");
					$event->setCancelled(true);
				}
			}
		}
	}

	public function onDeath(PlayerDeathEvent $event){
		if($this->isActive()){
			$event->setDrops([Item::get(Item::ENCHANTING_TABLE, 0, 1)]);
		}
	}
}
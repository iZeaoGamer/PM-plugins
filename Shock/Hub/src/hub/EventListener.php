<?php

namespace hub;

use hub\tasks\CheckStatusTask;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;

class EventListener implements Listener{
	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		$this->plugin = $plugin;
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$player->getInventory()->setItem(0, Item::get(Item::COMPASS)->setCustomName(TF::RESET . "Server Selector"));

		$event->setJoinMessage("");
	}

	public function onDrop(PlayerDropItemEvent $event){
		$event->setCancelled();
	}

	public function onQuit(PlayerQuitEvent $event){
		$event->setQuitMessage("");
	}

	public function onInteract(PlayerInteractEvent $event){
		$player = $event->getPlayer();
		$hand = $player->getInventory()->getItemInHand();

		if($event->getAction() !== PlayerInteractEvent::RIGHT_CLICK_AIR) return;
		switch($hand->getId()){
			case Item::COMPASS:
				$form = new SimpleForm(function(Player $player, $data){
					if($data === null) return;
					$config = $this->plugin->getConfig()->getAll();
					$arrayKey = array_keys($config);
					if(isset($arrayKey[$data])){
						$player->transfer("play.theshocknetwork.com", (int) $config[$arrayKey[$data]][0]);
					}
				});
				$form->setTitle("Server Selector");
				foreach($this->plugin->getConfig()->getAll() as $serverName => $data){
					if(!$data[1]){
						$form->addButton($serverName . "\n" . CheckStatusTask::$serverData[$data[0]]);
					}elseif($player->hasPermission("shock.bypass") && $data[1]){
						$form->addButton("PRIVATE: " . $serverName . "\n" . CheckStatusTask::$serverData[$data[0]]);
					}
				}
				$player->sendForm($form);
				$event->setCancelled();
				break;
		}

	}

	public function onBreak(BlockBreakEvent $event){
		$player = $event->getPlayer();
		if(!$player->hasPermission("shock.bypass")){
			$event->setCancelled();
		}
	}

	public function onPlace(BlockPlaceEvent $event){
		$player = $event->getPlayer();
		if(!$player->hasPermission("shock.bypass")){
			$event->setCancelled();
		}
	}

	public function handleExhaust(PlayerExhaustEvent $ev){
		$ev->setCancelled();
	}

	public function onDamage(EntityDamageEvent $event){
		$event->setCancelled();
	}
}
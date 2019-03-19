<?php
declare(strict_types=1);

namespace Adrenaline\Listener;

use Adrenaline\Loader;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\inventory\InventoryOpenEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;

/**
 * Class BasicAuthListener
 *
 * @package Adrenaline\Listener
 */
class BasicAuthListener implements Listener {

	private $plugin;

	/**
	 * BasicAuthListener constructor.
	 *
	 * @param Loader $loader
	 */
	public function __construct(Loader $loader){
		$loader->getServer()->getPluginManager()->registerEvents($this, $loader);
		$this->plugin = $loader;
	}

	/**
	 * @param PlayerMoveEvent $event
	 */
	public function onPlayerMove(PlayerMoveEvent $event){
		if(!$this->plugin->getAPI()->getAuthManager()->isPlayerAuthenticated($event->getPlayer())){
			$event->setCancelled(true);
			$event->getPlayer()->onGround = true;
		}
	}

	/**
	 * @param PlayerInteractEvent $event
	 */
	public function onPlayerInteract(PlayerInteractEvent $event){
		if(!$this->plugin->getAPI()->getAuthManager()->isPlayerAuthenticated($event->getPlayer())){
			$event->setCancelled(true);
		}
	}

	/**
	 * @param PlayerDropItemEvent $event
	 */
	public function onPlayerDropItem(PlayerDropItemEvent $event){
		if(!$this->plugin->getAPI()->getAuthManager()->isPlayerAuthenticated($event->getPlayer())){
			$event->setCancelled(true);
		}
	}

	/**
	 * @param PlayerQuitEvent $event
	 */
	public function onPlayerQuit(PlayerQuitEvent $event){
		$this->plugin->getAPI()->getAuthManager()->closePlayer($event->getPlayer());
	}

	/**
	 * @param PlayerItemConsumeEvent $event
	 */
	public function onPlayerItemConsume(PlayerItemConsumeEvent $event){
		if(!$this->plugin->getAPI()->getAuthManager()->isPlayerAuthenticated($event->getPlayer())){
			$event->setCancelled(true);
		}
	}

	/**
	 * @param EntityDamageEvent $event
	 */
	public function onEntityDamage(EntityDamageEvent $event){
		$entity = $event->getEntity();
		if($entity instanceof Player and !$this->plugin->getAPI()->getAuthManager()->isPlayerAuthenticated($entity)){
			$event->setCancelled(true);
		}
	}

	/**
	 * @param BlockBreakEvent $event
	 */
	public function onBlockBreak(BlockBreakEvent $event){
		if($event->getPlayer() instanceof Player and !$this->plugin->getAPI()->getAuthManager()->isPlayerAuthenticated($event->getPlayer())){
			$event->setCancelled(true);
		}
	}

	/**
	 * @param BlockPlaceEvent $event
	 */
	public function onBlockPlace(BlockPlaceEvent $event){
		if($event->getPlayer() instanceof Player and !$this->plugin->getAPI()->getAuthManager()->isPlayerAuthenticated($event->getPlayer())){
			$event->setCancelled(true);
		}
	}

	/**
	 * @param InventoryOpenEvent $event
	 */
	public function onInventoryOpen(InventoryOpenEvent $event){
		if(!$this->plugin->getAPI()->getAuthManager()->isPlayerAuthenticated($event->getPlayer())){
			$event->setCancelled(true);
		}
	}

	/**
	 * @param InventoryPickupItemEvent $event
	 */
	public function onPickupItem(InventoryPickupItemEvent $event){
		$player = $event->getInventory()->getHolder();
		if($player instanceof Player and !$this->plugin->getAPI()->getAuthManager()->isPlayerAuthenticated($player)){
			$event->setCancelled(true);
		}
	}
}
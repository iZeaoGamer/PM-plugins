<?php

namespace uhc;

use jojoe77777\FormAPI\ModalForm;
use jojoe77777\FormAPI\SimpleForm;
use network\NetworkLoader;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\inventory\InventoryOpenEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\inventory\EnchantInventory;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\EnchantmentList;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\GameRulesChangedPacket;
use pocketmine\network\mcpe\protocol\InventoryContentPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\network\mcpe\protocol\types\ContainerIds;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\utils\TextFormat;
use uhc\timers\UHCTimer;
use function array_keys;

class EventListener implements Listener{
	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		$this->plugin = $plugin;
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	public function handleReceive(DataPacketReceiveEvent $ev){
		$packet = $ev->getPacket();
		if($packet instanceof LevelSoundEventPacket){
			if($packet->sound === LevelSoundEventPacket::SOUND_ATTACK_NODAMAGE){
				if(!$ev->getPlayer()->hasPermission("shock.bypass")){
					$ev->setCancelled();
				}
			}
		}
	}

	public function handlePlayerCreation(PlayerCreationEvent $ev){
		$ev->setPlayerClass(UHCPlayer::class);
	}

	public function onChat(PlayerChatEvent $event){
		/** @var UHCPlayer $player */
		$player = $event->getPlayer();
		if($this->plugin->globalMute){
			if(!$player->hasPermission("shock.bypass")){
				$player->sendMessage(NetworkLoader::selectPrefix("UHC") . TextFormat::RED . "You cannot talk while Global Mute enabled!");
				$event->setCancelled(true);
			}
		}
		if($player->getGamemode() === Player::SPECTATOR){
			if(!$player->hasPermission("shock.bypass")){
				$player->sendMessage(NetworkLoader::selectPrefix("UHC") . TextFormat::RED . "You cannot talk as a spectator!");
				$event->setCancelled(true);
			}
		}
	}

	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();

		$pk = new MobEquipmentPacket();
		$pk->entityRuntimeId = $player->getId();
		$pk->item = Item::get(Item::TNT);
		$pk->hotbarSlot = $player->getInventory()->getHotbarSlotItem($player->getInventory()->getHeldItemIndex());
		$pk->inventorySlot = $player->getInventory()->getHotbarSlotItem($player->getInventory()->getHeldItemIndex());
		$pk->windowId = ContainerIds::OFFHAND;
		$player->dataPacket($pk);

		$pk2 = new InventoryContentPacket();
		$pk2->windowId = ContainerIds::OFFHAND;
		$pk2->items = [Item::get(Item::TNT)];
		$player->dataPacket($pk2);
		if(!$player->hasPlayedBefore()){
			if($this->plugin->gameStatus <= UHCTimer::STATUS_COUNTDOWN){
				$form = new ModalForm(function(Player $player, $data){
					if($data){
						$player->sendMessage("Added you to the queue!");
						$player->setGamemode(0);
					}else{
						$player->sendMessage("Removed you from the queue!");
						$player->setGamemode(3);
					}
				});

				$form->setContent("Do you want to play this UHC? If you select spectate, you will be unable to play the UHC at any point!");
				$form->setButton1("Play!");
				$form->setButton2("Spectate!");
				$player->sendForm($form);
			}
		}
		$pk = new GameRulesChangedPacket();
		$pk->gameRules = ["showcoordinates" => [1, true]];
		$player->dataPacket($pk);

		$event->setJoinMessage("");
	}

	public function onQuit(PlayerQuitEvent $event){
		$player = $event->getPlayer();
		if(isset($this->plugin->queue[$player->getName()])){
			unset($this->plugin->queue[$player->getName()]);
		}
		$event->setQuitMessage("");
	}

	public function handleEntityRegain(EntityRegainHealthEvent $ev){
		if($ev->getRegainReason() === EntityRegainHealthEvent::CAUSE_SATURATION){
			$ev->setCancelled();
		}
	}

	public function onDamage(EntityDamageEvent $event){
		switch($this->plugin->gameStatus){
			case UHCTimer::STATUS_WAITING:
			case UHCTimer::STATUS_COUNTDOWN:
				$event->setCancelled();
				break;
			case UHCTimer::STATUS_GRACE:
				if($event instanceof EntityDamageByEntityEvent){
					$event->setCancelled();
				}
				break;
		}
	}

	public function onDeath(PlayerDeathEvent $event){
		/** @var UHCPlayer $player */
		$player = $event->getPlayer();
		$cause = $player->getLastDamageCause();
		$player->setGamemode(3);
		$player->sendMessage(NetworkLoader::selectPrefix("UHC") . TextFormat::YELLOW . "You have been eliminated, use /spectate command to spectate a player!");
		if($cause instanceof EntityDamageByEntityEvent){
			$damager = $cause->getDamager();
			if($damager instanceof UHCPlayer){
				$this->plugin->addElimination($damager);
				$event->setDeathMessage(TextFormat::RED . $player->getName() . TextFormat::GRAY . "[" . TextFormat::WHITE . $this->plugin->getEliminations($player) . TextFormat::GRAY . "]" . TextFormat::YELLOW . " was slain by " . TextFormat::RED . $damager->getName() . TextFormat::GRAY . "[" . TextFormat::WHITE . $this->plugin->getEliminations($damager) . TextFormat::GRAY . "]");
			}
		}
	}

	public function onBreak(BlockBreakEvent $event){
		$player = $event->getPlayer();
		if($player instanceof UHCPlayer){
			switch($this->plugin->gameStatus){
				case UHCTimer::STATUS_WAITING:
				case UHCTimer::STATUS_COUNTDOWN:
					$event->setCancelled();
					break;
			}
		}
	}

	public function onPlace(BlockPlaceEvent $event){
		$player = $event->getPlayer();
		if($player instanceof UHCPlayer){
			switch($this->plugin->gameStatus){
				case UHCTimer::STATUS_WAITING:
				case UHCTimer::STATUS_COUNTDOWN:
					$event->setCancelled();
					break;
			}
		}
	}

	//TODO: Remove
	private $registeredEnchants = [
		"Protection" => Enchantment::PROTECTION,
		"Fire Protection" => Enchantment::FIRE_PROTECTION,
		"Feather Falling" => Enchantment::FEATHER_FALLING,
		"Blast Protection" => Enchantment::BLAST_PROTECTION,
		"Projectile Protection" => Enchantment::PROJECTILE_PROTECTION,
		"Thorns" => Enchantment::THORNS,
		"Respiration" => Enchantment::RESPIRATION,
		"Sharpness" => Enchantment::SHARPNESS,
		"Knockback" => Enchantment::KNOCKBACK,
		"Fire Aspect" => Enchantment::FIRE_ASPECT,
		"Efficiency" => Enchantment::EFFICIENCY,
		"Silk Touch" => Enchantment::SILK_TOUCH,
		"Unbreaking" => Enchantment::UNBREAKING,
		"Power" => Enchantment::POWER,
		"Punch" => Enchantment::PUNCH,
		"Flame" => Enchantment::FLAME,
		"Infinity" => Enchantment::INFINITY,
		"Mending" => Enchantment::MENDING,
		"Vanishing" => Enchantment::VANISHING
	];

	public function handleTableWindow(InventoryOpenEvent $ev){
		if($ev->getInventory() instanceof EnchantInventory){
			$form = new SimpleForm(function(UHCPlayer $player, $data){
				if($data === null) return;
				$hand = $player->getInventory()->getItemInHand();
				$arrayKey = array_keys($this->registeredEnchants);
				if($arrayKey[$data]){
					$enchantId = $this->registeredEnchants[$arrayKey[$data]];
					$enchant = Enchantment::getEnchantment($enchantId);
					$maxLevel = $enchant->getMaxLevel();
					$handEnchant = $hand->getEnchantment($enchantId);
					if($hand->hasEnchantment($enchantId)){
						$checkLevel = $handEnchant->getLevel() < $maxLevel ? $handEnchant->getLevel() + 1 : $maxLevel;
					}else{
						$checkLevel = 1;
					}

					$levelIncrease = $hand->hasEnchantment($enchantId) ? $handEnchant->getLevel() * 5 : 5;
					if($player->getXpLevel() >= $levelIncrease){
						$player->subtractXpLevels($levelIncrease);
						$hand->addEnchantment((new EnchantmentInstance($enchant))->setLevel($checkLevel));
						$player->getInventory()->setItemInHand($hand);
					}else{
						$neededLevels = (int) $levelIncrease - (int) $player->getXpLevel();
						$player->sendMessage("You need $neededLevels more level(s).");
					}
				}
			});

			$form->setTitle("Enchant Table");
			$form->setContent("Hold the item you want to enchant in your hand.");
			foreach(array_keys($this->registeredEnchants) as $enchantNames){
				$form->addButton($enchantNames);
			}
			$ev->getPlayer()->sendForm($form);
			$ev->setCancelled();
		}
	}
}
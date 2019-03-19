<?php
declare(strict_types=1);

namespace Adrenaline\Listener;

use Adrenaline\Loader;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\TextFormat;

/**
 * Class PlayerListener
 *
 * @package Adrenaline\Listener
 */
class PlayerListener implements Listener {

	public $plugin, $event2;
	public $pos = [];
	public $queue = [];

	/**
	 * PlayerListener constructor.
	 *
	 * @param Loader $loader
	 */
	public function __construct(Loader $loader){
		$this->plugin = $loader;
		$loader->getServer()->getPluginManager()->registerEvents($this, $loader);
	}

	/**
	 * @param EntityRegainHealthEvent $event
	 */
	public function onRegen(EntityRegainHealthEvent $event){
		if($event->getRegainReason() === EntityRegainHealthEvent::CAUSE_SATURATION){
			$event->setCancelled();
		}
	}

	/**
	 * @param PlayerCommandPreprocessEvent $event
	 */
	public function onCommand(PlayerCommandPreprocessEvent $event){
		$message = $event->getMessage();
		$sender = $event->getPlayer();
		if(preg_match("/@a /i", $message)){
			$cmd = $message;
			foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
				$cmd = $message;
				$cmd = str_ireplace("@a ", $player->getName() . " ", $cmd);
				$cmd = str_ireplace("/", "", $cmd);
				$this->plugin->getServer()->dispatchCommand($sender, $cmd);
			}
			$event->setCancelled(true);
		}

		if(!$this->plugin->getAPI()->getAuthManager()->isPlayerAuthenticated($event->getPlayer())){
			$message = $event->getMessage();
			if($message{0} === "/"){ //Command
				$event->setCancelled(true);
				$command = substr($message, 1);
				$args = explode(" ", $command);
				if($args[0] === "register" or $args[0] === "login"){
					$this->plugin->getServer()->dispatchCommand($event->getPlayer(), $command);
				}
			}

			$event->setCancelled();
		}
	}

	/**
	 * @param PlayerJoinEvent $event
	 */
	public function onJoin(PlayerJoinEvent $event){
		$event->setJoinMessage("");

		$player = $event->getPlayer();

		$config = $this->plugin->getAPI()->getAuthManager()->getPlayer($player);

		if($event->getPlayer()->getDeviceOS() === Loader::Gear_VR){
			$this->plugin->getAPI()->getAuthManager()->authenticatePlayer($player);
			$player->sendMessage("Logged you in because you are on Gear VR.");
		}else{
			if($config["lastip"] === $player->getAddress()){
				$this->plugin->getAPI()->getAuthManager()->authenticatePlayer($player);
				$player->sendMessage("Logged in by IP!");
				return;
			}else{
				$this->plugin->getAPI()->getAuthManager()->deauthenticatePlayer($event->getPlayer());
			}
		}

		/*if ($this->plugin->getAPI()->getGroup($event->getPlayer()) != null){
			$permm = $this->plugin->permissions->get("Groups");
			$permission = $permm[$this->plugin->getAPI()->getGroup($event->getPlayer())]['permission'];
			foreach($permission as $perm){
				$this->plugin->getAPI()->addPermission($event->getPlayer(), $perm);
			}
		}else{
			$this->plugin->getLogger()->alert("NULL");
		}*/

		$player->setWhitelisted(true);

		if($player->isSurvival()){
			if(!isset($this->queue[$player->getName()])){
				$this->queue[$player->getName()] = $player->getName();
			}
		}

		if(isset($this->pos[$player->getName()])){
			$player->teleport($this->pos[$player->getName()]);
			unset($this->pos[$player->getName()]);
		}
	}

	/**
	 * @param PlayerChatEvent $event
	 */
	public function onChat(PlayerChatEvent $event){
		$player = $event->getPlayer();

		if($this->plugin->getAPI()->getGlobalMute()){
			if(!$player->isOp()){
				$event->setCancelled();
				$player->sendMessage("You cannot chat right now!");
			}
		}
	}

	/**
	 * @param PlayerDeathEvent $event
	 */
	public function onDeath(PlayerDeathEvent $event){
		$player = $event->getPlayer();

		//TODO: Add reason why the player died
		$event->setDeathMessage($this->plugin->getAPI()->getPrefix() . $player->getDisplayName() . " has been killed!");
		$player->setGamemode(3);
		$player->sendTitle(TextFormat::RED . "You died!", TextFormat::GOLD . "Do /spectate to spectate a player!");

		if(isset($this->queue[$player->getName()])){
			unset($this->queue[$player->getName()]);
		}
	}

	/**
	 * @param PlayerQuitEvent $event
	 */
	public function onQuit(PlayerQuitEvent $event){
		$player = $event->getPlayer();

		if(isset($this->queue[$player->getName()])){
			unset($this->queue[$player->getName()]);
		}

		if(!isset($this->pos[$player->getName()])){
			$this->pos[$player->getName()] = clone $player->getLevel()->getSafeSpawn($player->getPosition());
		}
	}
}
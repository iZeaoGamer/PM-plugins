<?php
declare(strict_types=1);

namespace network;

use network\tasks\JoinEventTask;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;

class NetworkListener implements Listener{

	private $plugin;

	public function __construct(NetworkCore $plugin){
		$this->plugin = $plugin;
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	public function handleCreation(PlayerCreationEvent $ev){
		$ev->setPlayerClass(AvoltexPlayer::class);
	}

	public function handleLogin(PlayerLoginEvent $ev){
		$player = $ev->getPlayer();
		$this->plugin->getUser()->sendLoginData($player);
	}

	public function handleJoin(PlayerJoinEvent $ev){
		$player = $ev->getPlayer();
		$ev->setJoinMessage("");
		$this->plugin->getServer()->getScheduler()->scheduleDelayedTask(new JoinEventTask($this->plugin, $player), 16);
	}

	public function handleQuit(PlayerQuitEvent $ev){
		$ev->setQuitMessage("");
	}

	public function handleChat(PlayerChatEvent $ev){
		$ev->setFormat($this->plugin->getUser()->getChatFormat($ev->getPlayer(), $ev->getMessage()));
	}
}
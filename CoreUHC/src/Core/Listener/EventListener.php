<?php

namespace Core\Listener;

use Core\Loader;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\Item;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class EventListener implements Listener {

    private $plugin;
    public $config;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
    }

    public function getPlugin(){
        return $this->plugin;
    }
    public function onJoin(PlayerJoinEvent $join) {
        $join->getPlayer()->setGamemode(0);
        $join->setJoinMessage(TextFormat::GREEN . TextFormat::BOLD . "[+] " . TextFormat::RESET . TextFormat::GREEN . $join->getPlayer()->getName());
    }

    public function onQuit(PlayerQuitEvent $death) {
        $death->setQuitMessage(TextFormat::RED . TextFormat::BOLD . "[-] " . TextFormat::RESET . TextFormat::RED . $death->getPlayer()->getName());
    }

    public function onDeath(PlayerDeathEvent $death) {
        $death->getPlayer()->setGamemode(3);
        $death->getPlayer()->setNametag(TextFormat::GRAY . "DEAD " . $death->getPlayer()->getName());
        $death->getPlayer()->setDisplayName(TextFormat::GRAY . "DEAD " . $death->getPlayer()->getName());
    }
    
    public function onBreak(BlockBreakEvent $break){
        $this->config = new Config($this->plugin->getDataFolder() . "scenarios.yml");
        if($this->config->get("cutclean") === true){
            if($break->getBlock()->getId() === Item::IRON_ORE){
                $break->setDrops(array(Item::get(Item::IRON_INGOT)));
            }
        }
    }

}

<?php

namespace PrivateVault;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntArrayTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Tile;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use PrivateVault\Inv\CustomInv;
use PrivateVault\Inv\CustomTile;


class Main extends PluginBase implements Listener
{

    public $using = array();

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        if (!is_dir($this->getDataFolder())) mkdir($this->getDataFolder());
        if (!is_dir($this->getDataFolder() . "players/")) mkdir($this->getDataFolder() . "players/");
        Tile::registerTile(CustomTile::class);
    }

    public function onJoin(PlayerJoinEvent $event){
        $this->using[strtolower($event->getPlayer()->getName())] = null;
    }

    public function onBreak(BlockBreakEvent $event){
        if ($event->getBlock()->getLevel()->getTile($event->getBlock()) instanceof CustomTile) {
            $event->setCancelled();
        }
    }

    public function onInteract(PlayerInteractEvent $event) {
        if ($event->getBlock()->getLevel()->getTile($event->getBlock()) instanceof CustomTile) {
            $event->setCancelled();
        }
    }

    public function onInventoryClose(InventoryCloseEvent $event) {
        $inventory = $event->getInventory();
        $player = $event->getPlayer();
        if ($inventory instanceof CustomInv) {

            if ($this->using[strtolower($player->getName())] !== null) {
                if ($player instanceof Player) {
                    $player = $player->getName();
                }
                $player = strtolower($player);
                $cfg = new Config($this->getDataFolder() . "players/" . $player . ".yml", Config::YAML);
                for ($i = 0; $i < 27; $i++) {
                    $item = $inventory->getItem($i);
                    $id = $item->getId();
                    $damage = $item->getDamage();
                    $count = $item->getCount();
                    $enchantments = $item->getEnchantments();
                    $ens = array();
                    foreach ($enchantments as $en) {
                        $ide = $en->getId();
                        $level = $en->getLevel();
                        array_push($ens, array($ide, $level));
                    }
                    $number = $this->using[strtolower($event->getPlayer()->getName())];
                    $cfg->setNested("$number.items." . $i, array($id, $damage, $count, $ens));
                    $cfg->save();
                }
                $this->using[strtolower($event->getPlayer()->getName())] = null;
            }
        }
    }

    public function onQuit(PlayerQuitEvent $event) {
        if ($this->using[strtolower($event->getPlayer()->getName())] !== null) {
            $chest = $event->getPlayer()->getLevel()->getTile(new Position($event->getPlayer()->x, $event->getPlayer()->y - 4, $event->getPlayer()->z));
            if ($chest instanceof CustomInv) {
                $inv = $chest->getInventory();
                $this->saveVault($event->getPlayer(), $inv, $this->using[strtolower($event->getPlayer()->getName())]);
                $this->using[strtolower($event->getPlayer()->getName())] = null;
            }
        }
    }

    public function saveVault($player, $inventory, $number) {
        if ($player instanceof Player) {
            $player = $player->getName();
        }
        $player = strtolower($player);
        if ($inventory instanceof CustomInv) {
            $cfg = new Config($this->getDataFolder() . "players/" . $player . ".yml", Config::YAML);
            for ($i = 0; $i < 26; $i++) {
                $item = $inventory->getItem($i);
                $id = $item->getId();
                $damage = $item->getDamage();
                $count = $item->getCount();
                $enchantments = $item->getEnchantments();
                $ens = array();
                foreach ($enchantments as $en) {
                    $id = $en->getId();
                    $level = $en->getLevel();
                    array_push($ens, array($id, $level));
                }
                $cfg->setNested("$number.items." . $i, array($id, $damage, $count, $ens));
                $cfg->save();
            }
        }
    }

    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
        if ($sender instanceof Player) {
            switch ($cmd->getName()) {
                case "pv":
                    if ($this->hasPrivateVault($sender)) {
                        if (empty($args[0])) {
                            $sender->addWindow($this->loadVault($sender, 0));
                            $this->using[strtolower($sender->getName())] = 0;
                            return true;
                        } else {
                            if ($args[0] < 1 || $args[0] > 10) {
                                $sender->sendMessage(TextFormat::RED . "Usage: " . TextFormat::GRAY . "/pv [1-10]");
                                return true;
                            } else {
                                if ($sender->hasPermission("privatevaults.permission" . $args[0])) {
                                    $sender->addWindow($this->loadVault($sender, $args[0]));
                                    $this->using[strtolower($sender->getName())] = (int)$args[0];
                                    return true;
                                } else {
                                    $sender->sendMessage(TextFormat::RED . "You do not have that many vaults available!");
                                    return true;
                                }
                            }
                        }
                    } else {
                        $sender->sendMessage(TextFormat::YELLOW . "Creating vault..");
                        for ($i = 0; $i < 10; $i++) {
                            $this->createVault($sender, $i);
                        }
                        $sender->sendMessage(TextFormat::YELLOW . "Vault created! Run the command again to open it!");
                        return true;
                    }
            }
        }
    }

    public function hasPrivateVault($player) {
        if ($player instanceof Player) {
            $player = $player->getName();
        }
        $player = strtolower($player);
        return is_file($this->getDataFolder() . "players/" . $player . ".yml");
    }

    public function loadVault(Player $player, $number) {
        $block = new \pocketmine\block\Chest();
        $nbt = new CompoundTag("", [
            new ListTag("Items", []),
            new StringTag("id", Tile::CHEST),
            new IntTag("x", floor($player->getX())),
            new IntTag("y", floor($player->getY() + 1)),
            new IntTag("z", floor($player->getZ()))
        ]);
        $nbt->Items->setTagType(NBT::TAG_Compound);
        $tile = Tile::createTile("CustomTile", $player->getLevel(), $nbt);
        $tile->namedtag->replace = new IntArrayTag("replace", [$tile->getBlock()->getId(), $tile->getBlock()->getDamage()]);
        $block->x = floor($tile->x);
        $block->y = floor($tile->y);
        $block->z = floor($tile->z);
        $block->level = $tile->getLevel();
        $block->level->sendBlocks([$player], [$block]);

        if ($player instanceof Player) {
            $player = $player->getName();
        }
        $player = strtolower($player);
        $cfg = new Config($this->getDataFolder() . "players/" . $player . ".yml", Config::YAML);
        $tile->getInventory()->clearAll();
        for ($i = 0; $i < 27; $i++) {
            $ite = $cfg->getNested("$number.items." . $i);
            $item = Item::get($ite[0]);
            $item->setDamage($ite[1]);
            $item->setCount($ite[2]);
            foreach ($ite[3] as $key => $en) {
                $enchantment = Enchantment::getEnchantment($en[0]);
                $enchantment->setLevel($en[1]);
                $item->addEnchantment($enchantment);
            }
            $tile->getInventory()->setItem($i, $item);
        }
        return $tile->getInventory();
    }

    public function createVault($player, $number) {
        if ($player instanceof Player) {
            $player = $player->getName();
        }
        $player = strtolower($player);
        $cfg = new Config($this->getDataFolder() . "players/" . $player . ".yml", Config::YAML);
        $cfg->set("items", array());
        for ($i = 0; $i < 27; $i++) {
            $cfg->setNested("$number.items." . $i, array(0, 0, 0, array()));
        }
        $cfg->save();
    }
}
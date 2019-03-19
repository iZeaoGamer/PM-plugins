<?php
declare(strict_types=1);

namespace Adrenaline\Commands;

use Adrenaline\BaseFiles\BaseCommand;
use Adrenaline\Loader;
use pocketmine\command\CommandSender;
use pocketmine\entity\Effect;
use pocketmine\item\Item;
use pocketmine\Player;

class MeetupCommand extends BaseCommand {
	/**
	 * MeetupCommand constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "meetup", "Meetup command!", "/meetup [start]", []);
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		if($sender instanceof Player){
			if(in_array($this->getPlugin()->getAPI()->getGroup($sender), ["mod", "owner"])){
				if(isset($args[0])){
					switch($args[0]){
						case "start":
							foreach($this->getPlugin()->getServer()->getOnlinePlayers() as $p){
								$player = $p->getInventory();
								$p->addEffect(Effect::getEffect(Effect::INVISIBILITY)->setDuration(25 * 20));
								$player->addItem(Item::get(Item::DIAMOND_SWORD,0,1));
								$player->addItem(Item::get(Item::GOLDEN_APPLE, 0, 16));
								$player->addItem(Item::get(Item::DIAMOND_PICKAXE));
								$player->addItem(Item::get(Item::COBBLESTONE, 0, 64));
								$player->addItem(Item::get(Item::DIAMOND_AXE));
								$player->addItem(Item::get(Item::PLANK, 0, 64));
								$player->addItem(Item::get(Item::WATER, 0, 1));
								$player->addItem(Item::get(Item::LAVA, 0 , 1));
								$player->addItem(Item::get(Item::BUCKET, 0, 5));
								$p->addXpLevel(25);
								$player->setHelmet(Item::get(Item::DIAMOND_HELMET));
								$player->setChestplate(Item::get(Item::DIAMOND_CHESTPLATE));
								$player->setLeggings(Item::get(Item::DIAMOND_LEGGINGS));
								$player->setBoots(Item::get(Item::DIAMOND_BOOTS));
							}
							break;
					}
				}
			}
		}
	}
}
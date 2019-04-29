<?php

namespace uhc\scenarios;

use uhc\Loader;

use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;
use pocketmine\inventory\ShapedRecipe;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\tile\Tile;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\event\player\PlayerDeathEvent;

class HeadPole extends Scenario{
	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		parent::__construct($plugin, "HeadPole", ["hp"]);
		$this->plugin = $plugin;
		$this->registerRecipe();
	}

	public function onDeath(PlayerDeathEvent $event){
		$player = $event->getPlayer();
		if($this->isActive()){
			$level = $player->getLevel();
			$level->setBlock(new Vector3($player->x, $player->y + 1, $player->z), Block::get(Block::SKULL_BLOCK), true, true);
			$level->setBlock(new Vector3($player->x, $player->y, $player->z), Block::get(Block::NETHER_BRICK_FENCE));
			$nbt = new CompoundTag("", [
				new StringTag("id", Tile::SKULL),
				new StringTag("SkullType", 3),
				new IntTag("x", $player->x),
				new IntTag("y", $player->y + 1),
				new IntTag("z", $player->z),
				new StringTag("Rot", 0)
			]);
			Tile::createTile("Skull", $level, $nbt);
		}
	}

	public function registerRecipe(){
		$recipe = new ShapedRecipe(["aaa", "aba", "aaa"], [
			"a" => Item::get(Item::GOLD_INGOT, 0, 1),
			"b" => Item::get(Item::SKULL, 0, 1)
		], [Item::get(Item::GOLDEN_APPLE, 1, 1)->setCustomName(TextFormat::GOLD . "Golden Head")]);
		$this->plugin->getServer()->getCraftingManager()->registerRecipe($recipe);
	}
}
<?php

namespace uhc\scenarios;

use uhc\Loader;
use uhc\scenarios\tasks\BombTask;
use pocketmine\math\Vector3;
use pocketmine\level\Position;
use pocketmine\tile\Chest;
use pocketmine\tile\Tile;
use pocketmine\block\Block;
use pocketmine\event\player\PlayerDeathEvent;

class Timebomb extends Scenario{
	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		parent::__construct($plugin, "Timebomb", ["tb"]);
		$this->plugin = $plugin;
	}

	public function onDeath(PlayerDeathEvent $event){
		if($this->isActive()){
			$player = $event->getPlayer();
			$position = $player->getPosition();
			/** @var Chest $tile */
			$tile = $this->createChest($position);
			/** @var Chest $tile2 */
			$tile2 = $this->createChest(new Position($position->x + 1, $position->y, $position->z, $position->getLevel()));
			$tile->pairWith($tile2);
			$tile2->pairWith($tile);
			$drops = $event->getDrops();
			$tile->getInventory()->setContents($drops);
			$event->setDrops([]);
			$this->plugin->getScheduler()->scheduleRepeatingTask(new BombTask($this->plugin, $player, $player->getPosition()), 20);
		}
	}

	private function createChest(Position $position){
		$chest = Tile::createTile("Chest", $position->level, Chest::createNBT($position));
		$position->level->setBlock(new Vector3($chest->getX(), $chest->getY(), $chest->getZ()), Block::get(Block::CHEST), true, true);

		return $chest;
	}
}
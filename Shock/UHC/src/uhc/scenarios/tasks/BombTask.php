<?php

namespace uhc\scenarios\tasks;

use network\NetworkLoader;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\level\Explosion;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\tile\Sign;
use pocketmine\tile\Tile;
use pocketmine\scheduler\Task;
use uhc\Loader;

class BombTask extends Task{
	/** @var Loader */
	private $plugin;
	/** @var Position */
	private $pos;
	/** @var Sign */
	private $sign;
	/** @var Player */
	private $player;
	/** @var int */
	private $time = 31;

	public function __construct(Loader $plugin, Player $player, Position $position){
		$this->plugin = $plugin;
		$this->player = $player;
		$this->pos = $position;

		$vector = $position->getSide(Vector3::SIDE_SOUTH);

		$player->getLevel()->setBlock($vector, BlockFactory::get(Block::WALL_SIGN, 3), true);
		$this->sign = Tile::createTile(Tile::SIGN, $position->getLevel(), Sign::createNBT($vector));
	}

	public function onRun($currentTick){
		$this->time--;

		if(!$this->sign->isClosed()){
			$this->sign->setText($this->time);
		}

		if($this->time < 1){
			$this->plugin->getServer()->broadcastMessage(NetworkLoader::selectPrefix("Timebomb") . $this->player->getDisplayName() . "'s corpse has exploded!");

			$ex = new Explosion($this->pos, 5);
			$ex->explodeA();
			$ex->explodeB();

			$this->cancel();
		}
	}

	public function cancel(){
		$this->plugin->getScheduler()->cancelTask($this->getTaskId());
	}
}
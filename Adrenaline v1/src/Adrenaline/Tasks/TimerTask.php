<?php

declare(strict_types=1);

namespace Adrenaline\Tasks;

use Adrenaline\Loader;
use pocketmine\level\Position;
use pocketmine\scheduler\PluginTask;
use pocketmine\Server;

/**
 * Class TimerTask
 *
 * @package Adrenaline\Tasks
 */
class TimerTask extends PluginTask {

	public $plugin;
	public $timer = 6; //Value: 6
	public $grace = 10; //Value: 1201
	public $pvp = 10; // Value: 1201
	public $border = 2000; //Border is always 2000

	//TODO: Combine shrink
	public $shrink1 = 15; //Value: 301
	public $shrink2 = 15; //Value: 301
	public $shrink3 = 15; //Value: 301
	public $finalshrink = 301; //Value: 301

	/**
	 * TimerTask constructor.
	 *
	 * @param Loader $owner
	 */
	public function __construct(Loader $owner){
		parent::__construct($owner);
		$this->plugin = $owner;

	}

	/**
	 * @param $toCentre
	 * @param $checkAgainst
	 *
	 * @return string
	 */
	public static function centerText($toCentre, $checkAgainst){
		if(strlen($toCentre) >= strlen($checkAgainst)){
			return $toCentre;
		}

		$times = floor((strlen($checkAgainst) - strlen($toCentre)) / 2);

		return str_repeat(" ", ($times > 0 ? $times : 0)) . $toCentre;
	}

	/**
	 * @param $int
	 *
	 * @return string
	 */
	public function seconds2string($int){
		$m = floor($int / 60);
		$s = floor($int % 60);
		return (($m < 10 ? "0" : "") . $m . ":" . ($s < 10 ? "0" : "") . $s);
	}

	public function cancelTask(){
		$this->plugin->getServer()->getScheduler()->cancelTask($this->getTaskId());
	}

	/**
	 * @param $currentTick
	 */
	public function onRun($currentTick){
		$this->sendTitle();
		$this->warpInBorder();
		$this->sendHealth();
	}


	public function sendHealth(){
		foreach(Server::getInstance()->getOnlinePlayers() as $p){
			$popup = "     AdrenalineUHC\n" . "  X: " . $p->getFloorX() . " Y: " . $p->getFloorY() . " Z: " . $p->getFloorZ() . "\n    Players Left: " . count($this->plugin->getAPI()->getPlayerListener()->queue) . "\n       Health: " . $p->getHealth() / 2;
			$p->sendTip($popup);
		}
	}

	public function sendTitle(){
		$this->timer--;
		foreach($this->plugin->getServer()->getOnlinePlayers() as $p){
			if($this->timer === 0){
				$p->sendTitle("UHC Started");
				//$p->getLevel()->broadcastLevelEvent($p, 2006);
			}
			if($this->timer > 0){
				$p->sendTitle((string) $this->timer);
				//$p->getLevel()->broadcastLevelEvent($p, LevelEventPacket::EVENT_SOUND_ANVIL_FALL);
			}
		}
	}

	public function callGrace(){
		if($this->timer <= 0 || $this->grace >= 0){
			$this->grace--;

			foreach(Server::getInstance()->getOnlinePlayers() as $p){
				$p->sendTip("AdrenalineUHC\n" . "X: " . round($p->getX()) . " Y: " . round($p->getY()) . " Z: " . round($p->getZ()) . "\nHealth: " . $p->getHealth() / 2 . "\nGrace will end in " . $this->seconds2string($this->grace));
			}

			if($this->grace === 0){
				$this->plugin->getAPI()->setDifficulty(1);
				Server::getInstance()->broadcastMessage($this->plugin->getAPI()->getPrefix() . "PvP has been enabled!");
			}
		}
	}

	public function callPvP(){
		if($this->grace <= 0 || $this->pvp >= 0){
			$this->pvp--;
			foreach(Server::getInstance()->getOnlinePlayers() as $p){
				$p->sendTip("AdrenalineUHC\n" . "X: " . round($p->getX()) . " Y: " . round($p->getY()) . " Z: " . round($p->getZ()) . "\nHealth: " . $p->getHealth() / 2 . "\nEverything is normal for " . $this->seconds2string($this->pvp));
			}
		}
	}

	/**
	 * @param $value
	 */
	public function setBorder($value){
		$this->border = $value;
	}

	/**
	 * @return bool
	 */
	public function warpInBorder(){
		$level = $this->plugin->getServer()->getDefaultLevel();
		foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
			if(($player->getFloorX() >= $this->border or $player->getFloorX() <= -$this->border or
					$player->getFloorZ() >= $this->border or $player->getFloorZ() <= -$this->border) && $player->getGamemode() == 0
			){
				$x = mt_rand(-$this->border, $this->border);
				$z = mt_rand(-$this->border, $this->border);
				if(!$level->isChunkLoaded($x, $z)){
					$level->loadChunk($x << 4, $z << 4);
				}
				$player->teleport(new Position($x, $level->getHighestBlockAt($x, $z) + 1, $z, $level));
				$player->sendMessage("Teleported you inside the border.");
			}
		}
		return false;
	}
}
<?php

namespace uhc\timers;

use network\NetworkLoader;
use network\utils\RegionUtils;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
use uhc\Loader;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TF;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\item\Item;
use uhc\UHCPlayer;
use network\utils\Scoreboard;
use function mt_rand;

class UHCTimer extends Task{
	/** @var int */
	public const STATUS_WAITING = -1;
	/** @var int */
	public const STATUS_COUNTDOWN = 0;
	/** @var int */
	public const STATUS_GRACE = 1;
	/** @var int */
	public const STATUS_PVP = 2;
	/** @var int */
	public const STATUS_NORMAL = 3;

	/** @var int */
	public $game = 0;
	/** @var int */
	public $countdown = 30;
	/** @var float|int */
	public $grace = 60 * 20;
	/** @var float|int */
	public $pvp = 60 * 30;
	/** @var float|int */
	public $normal = 60 * 60;
	/** @var int */
	private $border = 1000;
	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		$this->plugin = $plugin;
	}

	public function onRun($currentTick){
		foreach($this->plugin->getServer()->getOnlinePlayers() as $p){
			if($p->isSurvival()){
				if(!isset($this->plugin->queue[$p->getName()])){
					$this->plugin->queue[$p->getName()] = $p->getName();
				}
			}else{
				if(isset($this->plugin->queue[$p->getName()])){
					unset($this->plugin->queue[$p->getName()]);
				}
			}

			$p->setNameTag($p->getDisplayName() . TF::GOLD . " [" . $p->getDeviceName() . "]" . TF::EOL . TF::WHITE . round($p->getHealth()) . TF::RED . " ❤");

			$this->handleScoreboard($p);
			$this->teleportInBorder($p);
			switch($this->plugin->gameStatus){
				case self::STATUS_COUNTDOWN:
					$this->handleCountdown($p);
					break;
				case self::STATUS_GRACE:
					$this->handleGrace($p);
					break;
				case self::STATUS_PVP:
					$this->handlePvP($p);
					break;
			}
		}

		if($this->plugin->gameStatus >= self::STATUS_GRACE) $this->game++;
		switch($this->plugin->gameStatus){
			case self::STATUS_COUNTDOWN:
				$this->countdown--;
				break;
			case self::STATUS_GRACE:
				$this->grace--;
				break;
			case self::STATUS_PVP:
				$this->pvp--;
				break;
			case self::STATUS_NORMAL:
				$this->normal--;
				break;
		}
	}

	private function handleCountdown(UHCPlayer $p){
		$p->setMaxHealth(20);
		$p->setFood(20);
		$p->setHealth(20);
		$p->resetFallDistance();
		switch($this->countdown){
			case 29:
				//$this->buildBorder($this->border);
				$this->randomizeCoordinates($p, 1000);
				$p->sendMessage("Server has been " . TF::GOLD . "whitelisted!");
				$p->setWhitelisted(true);
				$this->plugin->getServer()->setConfigBool("white-list", true);
				$p->getInventory()->clearAll();
				$p->getArmorInventory()->clearAll();
				$p->getCursorInventory()->clearAll();
				$p->sendMessage("The game will begin in " . TF::GOLD . "30 seconds.");
				$p->setImmobile(true);
				break;
			case 28:
				$p->sendMessage("Global Mute has been " . TF::GOLD . "enabled!");
				$this->plugin->globalMute = true;
				break;
			case 10:
				$p->sendMessage("The game will begin in " . TF::GOLD . "10 seconds.");
				$p->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 10000000, 0, false));
				$p->getInventory()->addItem(Item::get(Item::STEAK, 0, 64));
				$p->getInventory()->addItem(Item::get(Item::LEATHER, 0, 32));
				break;
			case 5:
			case 4:
			case 3:
				$p->setImmobile(false);
				break;
			case 2:
			case 1:
				$p->sendMessage("The game will begin in " . TF::GOLD . "$this->countdown second(s).");
				break;
			case 0:
				$p->sendMessage(TF::RED . TF::BOLD . "The UHC has begun!");
				$this->plugin->gameStatus = self::STATUS_GRACE;
				$this->countdown = 30;
				break;
		}
	}

	private function handleGrace(UHCPlayer $p){
		switch($this->grace){
			case 1190:
				$p->sendMessage("Global Mute has been " . TF::GOLD . "disabled!");
				$this->plugin->globalMute = false;
				$p->sendMessage("Final heal will occur in " . TF::GOLD . "10 minutes.");
				break;
			case 601:
				$p->sendMessage("Final heal has occurred!");
				$p->setHealth(20);
				break;
			case 600:
				$p->sendMessage(TF::RED . "PvP will be enabled in 10 minutes.");
				break;
			case 300:
				$p->sendMessage(TF::RED . "PvP will be enabled in 5 minutes.");
				break;
			case 60:
				$p->sendMessage(TF::RED . "PvP will be enabled in 1 minute.");
				break;
			case 30:
				$p->sendMessage(TF::RED . "PvP will be enabled in 30 seconds.");
				break;
			case 10:
				$p->sendMessage(TF::RED . "PvP will be enabled in 10 seconds.");
				break;
			case 5:
			case 4:
			case 3:
			case 2:
			case 1:
				$p->sendMessage(TF::RED . "PvP will be enabled in $this->grace second(s).");
				break;
			case 0:
				$p->sendMessage(TF::RED . "PvP has been enabled, good luck!");
				$this->plugin->gameStatus = self::STATUS_PVP;
				$this->grace = 60 * 20;
				break;
		}
	}

	private function handlePvP(UHCPlayer $p){
		$borderPrefix = NetworkLoader::selectPrefix("Border");
		switch($this->pvp){
			case 1500:
				$p->sendMessage("$borderPrefix The border will shrink to " . TF::GOLD . "750" . TF::WHITE . " in " . TF::GOLD . "5 minutes");
				break;
			case 1200:
				$this->border = 750;
				//$this->buildBorder($this->border);
				$p->sendMessage("$borderPrefix The border has shrunk to " . TF::GOLD . $this->border);
				$p->sendMessage("$borderPrefix The border will shrink to " . TF::GOLD . "500" . TF::WHITE . " in " . TF::GOLD . "5 minutes");
				break;
			case 900:
				$this->border = 500;
				//$this->buildBorder($this->border);
				$p->sendMessage("$borderPrefix The border has shrunk to " . TF::GOLD . $this->border);
				$p->sendMessage("$borderPrefix The border will shrink to " . TF::GOLD . "250" . TF::WHITE . " in " . TF::GOLD . "5 minutes");
				break;
			case 600:
				$this->border = 250;
				//$this->buildBorder($this->border);
				$p->sendMessage("$borderPrefix The border has shrunk to " . TF::GOLD . $this->border);
				$p->sendMessage("$borderPrefix The border will shrink to " . TF::GOLD . "100" . TF::WHITE . " in " . TF::GOLD . "5 minutes.");
				break;
			case 300:
				$this->border = 100;
				//$this->buildBorder($this->border);
				$p->sendMessage("$borderPrefix The border has shrunk to " . TF::GOLD . $this->border);
				$p->sendMessage("$borderPrefix The border will shrink to " . TF::GOLD . "25" . TF::WHITE . " in " . TF::GOLD . "5 minutes.");
				break;
			case 0:
				$this->border = 25;
				//$this->buildBorder($this->border);
				$p->sendMessage("$borderPrefix The border has shrunk to " . TF::GOLD . $this->border);
				$this->plugin->gameStatus = self::STATUS_NORMAL;
				$this->pvp = 60 * 30;
				break;
		}
	}

	private function handleScoreboard(UHCPlayer $p){
		$scoreboard = new Scoreboard($p);
		$scoreboard->setTitle("");

		if($this->plugin->gameStatus >= self::STATUS_GRACE){
			$scoreboard->setLine(1, "§7---------------------");
			$scoreboard->setLine(2, " §3Game Time: §f" . gmdate("H:i:s", $this->game));
			$scoreboard->setLine(3, " §6Remaining: §f" . count($this->plugin->queue));
			$scoreboard->setLine(4, " §6Eliminations: §f" . $this->plugin->getEliminations($p));
			$scoreboard->setLine(5, " §6Border: §f" . $this->border);
			$scoreboard->setEmptyLine(7);
			$scoreboard->setLine(8, "§6 play.theshocknetwork.com");
			$scoreboard->setLine(9, "§7--------------------- ");
		}elseif($this->plugin->gameStatus <= self::STATUS_COUNTDOWN){
			$scoreboard->setLine(1, "§7---------------------");
			$scoreboard->setLine(2, " §3Players: §8" . count($this->plugin->queue));
			$scoreboard->setLine(3, $this->plugin->gameStatus === self::STATUS_COUNTDOWN ? "§3 Starting in:§8 $this->countdown" : "§3 Waiting for players");
			$scoreboard->setEmptyLine(4);
			$scoreboard->setLine(5, "§3 arium.network ");
			$scoreboard->setLine(6, "§7--------------------- ");
		}
	}

	private function teleportInBorder(UHCPlayer $p){
		if(($p->getX() > $this->border || $p->getZ() > $this->border || $p->getX() < -$this->border || $p->getZ() < -$this->border)){
			$x = mt_rand(5, 20);
			$z = mt_rand(5, 20);
			if($p->getX() < 0 && $p->getZ() < 0){
				$pX = -$this->border + $x;
				$pZ = -$this->border + $z;
			}elseif($p->getX() > 0 && $p->getZ() > 0){
				$pX = $this->border - $x;
				$pZ = $this->border - $z;
			}elseif($p->getX() < 0 && $p->getZ() > 0){
				$pX = -$this->border + $x;
				$pZ = $this->border - $z;
			}else{
				$pX = $this->border - $x;
				$pZ = -$this->border + $z;
			}

			RegionUtils::onChunkGenerated($p->getLevel(), $pX >> 4, $pZ >> 4, function() use ($p, $pX, $pZ){
				$p->teleport(new Vector3($pX, $p->getLevel()->getHighestBlockAt($pX, $pZ) + 1, $pZ));
				$p->sendMessage(NetworkLoader::selectPrefix("Border") . "You have been teleported by the border!");
			});
		}
	}

	private function randomizeCoordinates(UHCPlayer $p, int $range){
		$ss = $p->getLevel()->getSafeSpawn();
		$x = mt_rand($ss->getX() - $range, $ss->getX() + $range);
		$z = mt_rand($ss->getZ() - $range, $ss->getZ() + $range);

		RegionUtils::onChunkGenerated($p->getLevel(), $x >> 4, $z >> 4, function() use ($p, $x, $z){
			$p->teleport(new Vector3($x, 126, $z));
		});
	}

	public function buildBorder($border){
		$level = $this->plugin->getServer()->getDefaultLevel();

		for($minX = -$border; $minX <= $border; $minX++){
			RegionUtils::onChunkGenerated($level, $minX >> 4, $border >> 4, function() use ($level, $minX, $border){
				$highestBlock = $level->getHighestBlockAt($minX, $border);
				for($y = $highestBlock; $y <= $highestBlock + 4; $y++){
					$level->setBlock(new Vector3($minX, $y, $border), Block::get(Block::BEDROCK));
				}
			});
		}

		for($minX = -$border; $minX <= $border; $minX++){
			RegionUtils::onChunkGenerated($level, $minX >> 4, $border >> 4, function() use ($level, $minX, $border){
				$highestBlock = $level->getHighestBlockAt($minX, -$border);
				for($y = $highestBlock; $y <= $highestBlock + 4; $y++){
					$level->setBlock(new Vector3($minX, $y, -$border), Block::get(Block::BEDROCK));
				}
			});
		}

		for($minZ = -$border; $minZ <= $border; $minZ++){
			RegionUtils::onChunkGenerated($level, $minZ >> 4, $border >> 4, function() use ($level, $minZ, $border){
				$highestBlock = $level->getHighestBlockAt($border, $minZ);
				for($y = $highestBlock; $y <= $highestBlock + 4; $y++){
					$level->setBlock(new Vector3($border, $y, $minZ), Block::get(Block::BEDROCK));
				}
			});
		}

		for($minZ = -$border; $minZ <= $border; $minZ++){
			RegionUtils::onChunkGenerated($level, $minZ >> 4, $border >> 4, function() use ($level, $minZ, $border){
				$highestBlock = $level->getHighestBlockAt(-$border, $minZ);
				for($y = $highestBlock; $y <= $highestBlock + 4; $y++){
					$level->setBlock(new Vector3(-$border, $y, $minZ), Block::get(Block::BEDROCK));
				}
			});
		}
	}

}
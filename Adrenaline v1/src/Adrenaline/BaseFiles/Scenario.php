<?php
declare(strict_types=1);

namespace Adrenaline\BaseFiles;

use Adrenaline\Loader;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\inventory\FurnaceBurnEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\server\DataPacketReceiveEvent;

/**
 * Class Scenario
 *
 * @package Adrenaline\BaseFiles
 */
class Scenario implements ScenarioInterface {

	/** @var Loader */
	private $loader;

	/**@var bool $active */
	private $active;

	/** @var string $name */
	private $name;

	/** @var bool $isDefault */
	private $isDefault;

	/** @var string[] $aliases */
	private $aliases;

	/**
	 * Scenario constructor.
	 *
	 * @param Loader $loader
	 * @param string $name
	 * @param array  $aliases
	 * @param bool   $isDefault
	 */
	public function __construct(Loader $loader, string $name, array $aliases, bool $isDefault = false){
		$this->loader = $loader;
		$this->name = $name;
		$this->aliases = $aliases;
		$this->isDefault = $isDefault;
		if($isDefault){
			$this->active = true;
		}else{
			$this->active = false;
		}
	}

	/**
	 * @return Loader
	 */
	public function getLoader(){
		return $this->loader;
	}

	/**
	 * @return string
	 */
	public function getName(){
		return $this->name;
	}

	/**
	 * @return string[]
	 */
	public function getAliases(){
		return $this->aliases;
	}

	/**
	 * @param string[] $aliases
	 */
	public function setAliases(array $aliases){
		$this->aliases = $aliases;
	}

	/**
	 * @return bool
	 */
	public function isActive(){
		return $this->active;
	}

	/**
	 * @param string $string
	 *
	 * @return bool
	 */
	public function stringMatches(string $string){
		$string = strtolower($string);
		foreach($this->getAliases() as $alias){
			if(($string == strtolower($this->getName())) or ($string == strtolower($alias))){
				return true;
			}
		}
		return false;
	}

	/**
	 * @param bool $value
	 */
	public function setActive(bool $value){
		$this->active = $value;
	}


	/**
	 * @param PlayerDeathEvent $event
	 *
	 * @return mixed|void
	 */
	public function onDeath(PlayerDeathEvent $event){
	}

	/**
	 * @param BlockBreakEvent $event
	 *
	 * @return mixed|void
	 */
	public function onBreak(BlockBreakEvent $event){
	}

	/**
	 * @param EntityDamageEvent $event
	 *
	 * @return mixed|void
	 */
	public function onDamage(EntityDamageEvent $event){
	}

	/**
	 * @param EntityDeathEvent $event
	 *
	 * @return mixed|void
	 */
	public function onEntityDeath(EntityDeathEvent $event){
	}

	/**
	 * @param CraftItemEvent $event
	 *
	 * @return mixed|void
	 */
	public function onCraft(CraftItemEvent $event){
	}

	/**
	 * @param FurnaceBurnEvent $event
	 *
	 * @return mixed|void
	 */
	public function onBurn(FurnaceBurnEvent $event){
	}

	/**
	 * @param DataPacketReceiveEvent $event
	 *
	 * @return mixed|void
	 */
	public function onDataRecieve(DataPacketReceiveEvent $event){
	}
}
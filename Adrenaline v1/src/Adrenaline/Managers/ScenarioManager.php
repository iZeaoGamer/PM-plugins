<?php
declare(strict_types=1);

namespace Adrenaline\Managers;

use Adrenaline\BaseFiles\Scenario;
use Adrenaline\Loader;
use Adrenaline\Scenarios\BloodDiamond;
use Adrenaline\Scenarios\CutClean;
use Adrenaline\Scenarios\Diamondless;
use Adrenaline\Scenarios\Windows10;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\server\DataPacketReceiveEvent;

/**
 * Class ScenarioManager
 *
 * @package Adrenaline\Managers
 */
class ScenarioManager {

	private $plugin;
	/** @var Scenario[] */
	public $scenarios = [];

	/**
	 * ScenarioManager constructor.
	 *
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		$this->plugin = $plugin;
		$this->setScenarios();
	}

	/**
	 * @param Scenario $scenario
	 */
	public function addScenario(Scenario $scenario){
		$this->scenarios[$scenario->getName()] = $scenario;
	}


	/**
	 * @return Scenario[]
	 */
	public function getScenarios(){
		return $this->scenarios;
	}

	/**
	 * @return bool
	 */
	public function scenariosEmpty(){
		foreach($this->getScenarios() as $scenario){
			if($scenario->isActive()){
				return false;
			}
		}
		return true;
	}

	/**
	 * @return bool
	 */
	public function scenariosFull(){
		foreach($this->getScenarios() as $scenario){
			if(!$scenario->isActive()){
				return false;
			}
		}
		return true;
	}

	/**
	 * @param string $name
	 *
	 * @return Scenario|null
	 */
	public function getScenarioByName(string $name){
		$name = strtolower($name);
		foreach($this->scenarios as $scenario){
			if(strtolower($scenario->getName()) === $name){
				return $scenario;
			}
		}
		return null;
	}

	private function setScenarios(){
		$this->addScenario(new CutClean($this->plugin));
		$this->addScenario(new Diamondless($this->plugin));
		$this->addScenario(new BloodDiamond($this->plugin));
		$this->addScenario(new Windows10($this->plugin));
	}

	/**
	 * @param PlayerDeathEvent $event
	 */
	public function handleDeath(PlayerDeathEvent $event){
		foreach($this->getScenarios() as $scenario){
			try{
				if($scenario->isActive()){
					$scenario->onDeath($event);
				}
			}catch(\Exception $exception){

			}
		}
	}

	/**
	 * @param BlockBreakEvent $event
	 */
	public function handleBreak(BlockBreakEvent $event){
		foreach($this->getScenarios() as $scenario){
			try{
				if($scenario->isActive()){
					$scenario->onBreak($event);
				}
			}catch(\Exception $exception){

			}
		}
	}

	/**
	 * @param EntityDamageEvent $event
	 */
	public function handleDamage(EntityDamageEvent $event){
		foreach($this->getScenarios() as $scenario){
			try{
				if($scenario->isActive()){
					$scenario->onDamage($event);
				}
			}catch(\Exception $exception){

			}
		}
	}

	/**
	 * @param EntityDeathEvent $event
	 */
	public function handleEntityDeath(EntityDeathEvent $event){
		foreach($this->getScenarios() as $scenario){
			try{
				if($scenario->isActive()){
					$scenario->onEntityDeath($event);
				}
			}catch(\Exception $exception){

			}
		}
	}

	/**
	 * @param CraftItemEvent $event
	 */
	public function handleCraft(CraftItemEvent $event){
		foreach($this->getScenarios() as $scenario){
			try{
				if($scenario->isActive()){
					$scenario->onCraft($event);
				}
			}catch(\Exception $exception){

			}
		}
	}

	/**
	 * @param DataPacketReceiveEvent $event
	 */
	public function handleDataRecieve(DataPacketReceiveEvent $event){
		foreach($this->getScenarios() as $scenario){
			try{
				if($scenario->isActive()){
					$scenario->onDataRecieve($event);
				}
			}catch(\Exception $exception){

			}
		}
	}
}
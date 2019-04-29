<?php
declare(strict_types=1);

namespace uhc\managers;

use uhc\Loader;
use uhc\scenarios\Barebones;
use uhc\scenarios\Crippled;
use uhc\scenarios\CutClean;
use uhc\scenarios\DoubleHealth;
use uhc\scenarios\DoubleOrNothing;
use uhc\scenarios\EnchantedDeath;
use uhc\scenarios\Fireless;
use uhc\scenarios\NoClean;
use uhc\scenarios\NoFall;
use uhc\scenarios\Scenario;
use uhc\scenarios\Switcheroo;
use uhc\scenarios\Timebomb;
use uhc\scenarios\BloodDiamond;
use uhc\scenarios\DoubleOres;
use uhc\scenarios\MobileOnly;
use uhc\scenarios\HeadPole;

class ScenarioManager{
	/** @var Loader */
	private $plugin;
	/** @var Scenario[] array */
	private $scenarios = [];

	public function __construct(Loader $plugin){
		$this->plugin = $plugin;
		$this->registerScenario();
	}

	public function getScenarios(){
		return $this->scenarios;
	}

	public function addScenario(Scenario $scenario){
		$this->scenarios[$scenario->getName()] = $scenario;
	}

	public function registerScenario(){
		$this->addScenario(new Barebones($this->plugin));
		$this->addScenario(new BloodDiamond($this->plugin));
		$this->addScenario(new CutClean($this->plugin));
		$this->addScenario(new Crippled($this->plugin));
		$this->addScenario(new DoubleHealth($this->plugin));
		$this->addScenario(new DoubleOres($this->plugin));
		$this->addScenario(new DoubleOrNothing($this->plugin));
		$this->addScenario(new EnchantedDeath($this->plugin));
		$this->addScenario(new Fireless($this->plugin));
		$this->addScenario(new HeadPole($this->plugin));
		$this->addScenario(new MobileOnly($this->plugin));
		$this->addScenario(new NoClean($this->plugin));
		$this->addScenario(new NoFall($this->plugin));
		//$this->addScenario(new Switcheroo($this->plugin));
		//$this->addScenario(new Timebomb($this->plugin));
	}
}
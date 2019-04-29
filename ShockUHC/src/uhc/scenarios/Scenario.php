<?php
declare(strict_types=1);

namespace uhc\scenarios;

use pocketmine\event\Listener;
use uhc\Loader;

class Scenario implements Listener{
	/** @var Loader */
	private $loader;
	/** @var string */
	private $name;
	/** @var array */
	private $aliases;
	/** @var bool */
	private $activeScenario = false;

	public function __construct(Loader $loader, string $name, array $aliases){
		$loader->getServer()->getPluginManager()->registerEvents($this, $loader);
		$this->loader = $loader;
		$this->name = $name;
		$this->aliases = $aliases;
	}

	public function getName() : string{
		return $this->name;
	}

	public function getAliases() : array{
		return $this->aliases;
	}

	public function setActive(bool $active) : void{
		$this->activeScenario = $active;
	}

	public function isActive(){
		return $this->activeScenario;
	}

	/* Credits to Paradox for this function */
	public function stringMatches(string $string){
		$string = strtolower($string);
		foreach($this->getAliases() as $alias){
			if(($string == strtolower($this->getName())) or ($string == strtolower($alias))){
				return true;
			}
		}

		return false;
	}
}
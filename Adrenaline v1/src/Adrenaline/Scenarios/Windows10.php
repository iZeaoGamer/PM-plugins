<?php
declare(strict_types=1);

namespace Adrenaline\Scenarios;

use Adrenaline\BaseFiles\Scenario;
use Adrenaline\Loader;
use pocketmine\event\server\DataPacketReceiveEvent;

/**
 * Class Windows10
 *
 * @package Adrenaline\Scenarios
 */
class Windows10 extends Scenario {
	/**
	 * Windows10 constructor.
	 *
	 * @param Loader $loader
	 */
	public function __construct(Loader $loader){
		parent::__construct($loader, "Windows 10", ['w10'], true);
	}

	/**
	 * @param DataPacketReceiveEvent $event
	 *
	 * @return mixed|void
	 */
	public function onDataRecieve(DataPacketReceiveEvent $event){
		if(!$this->getLoader()->getAPI()->getScenarioManager()->getScenarioByName("Windows 10")->isActive()){
			//TODO
		}
	}
}
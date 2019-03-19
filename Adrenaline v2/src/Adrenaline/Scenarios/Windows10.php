<?php
declare(strict_types=1);

namespace Adrenaline\Scenarios;

use Adrenaline\BaseFiles\Scenario;
use Adrenaline\Loader;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;

class Windows10 extends Scenario implements Listener {
	/**
	 * Windows10 constructor.
	 *
	 * @param Loader $plugin
	 *
	 * @internal param Loader $loader
	 */
	public function __construct(Loader $plugin){
		parent::__construct($plugin, "Windows 10", ['w10'], true);
		$this->getLoader()->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	/**
	 * @param PlayerPreLoginEvent $event
	 *
	 * @return mixed|void
	 */
	public function onPreLogin(PlayerPreLoginEvent $event){
		if(!$this->isActive()){
			if($event->getPlayer()->getDeviceOS() === 7){
				$event->getPlayer()->kick("Windows 10 is not allowed", false);
			}
		}
	}
}
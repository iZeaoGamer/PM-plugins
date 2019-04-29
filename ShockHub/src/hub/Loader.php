<?php

namespace hub;

use hub\tasks\SideBarTask;

use pocketmine\plugin\PluginBase;

class Loader extends PluginBase{

	public function onEnable(){
		$this->getScheduler()->scheduleRepeatingTask(new SideBarTask($this), 20);
		new EventListener($this);
	}
}
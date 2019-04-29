<?php

namespace uhc\managers;

use uhc\{command\ScenariosCommand,
	command\TpAllCommand,
	command\UHCCommand,
	command\ReportCommand,
	command\GlobalMuteCommand,
	command\SpectatorCommand,
	Loader};

class CommandManager{
	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		$this->plugin = $plugin;
		$this->init();
	}

	private function init(){
		$this->plugin->getServer()->getCommandMap()->registerAll("uhc", [
			new UHCCommand($this->plugin),
			new ScenariosCommand($this->plugin),
			new ReportCommand($this->plugin),
			new GlobalMuteCommand($this->plugin),
			new SpectatorCommand($this->plugin),
			new TpAllCommand($this->plugin)
		]);
	}
}
<?php

declare(strict_types=1);

namespace Adrenaline\Managers;

use Adrenaline\Commands\EmailCommand;
use Adrenaline\Commands\GlobalMuteCommand;
use Adrenaline\Commands\HostCommand;
use Adrenaline\Commands\LoginCommand;
use Adrenaline\Commands\MeetupCommand;
use Adrenaline\Commands\PvPCommand;
use Adrenaline\Commands\RegisterCommand;
use Adrenaline\Commands\ScenarioCommand;
use Adrenaline\Commands\SetGroupCommand;
use Adrenaline\Commands\SpectateCommand;
use Adrenaline\Commands\TpallCommand;
use Adrenaline\Commands\TweetCommand;
use Adrenaline\Commands\WorldCommand;
use Adrenaline\Loader;

/**
 * Class CommandManager
 *
 * @package Adrenaline\Managers
 */
class CommandManager {

	private $plugin;

	/**
	 * CommandManager constructor.
	 *
	 * @param Loader $loader
	 */
	public function __construct(Loader $loader){
		$this->plugin = $loader;
		$this->init();
	}

	public function init(){
		$map = $this->plugin->getServer()->getCommandMap();

		$map->registerAll(
			"uhc", [
			new HostCommand($this->plugin),
			new PvPCommand($this->plugin),
			new MeetupCommand($this->plugin),
			new GlobalMuteCommand($this->plugin),
			new TweetCommand($this->plugin),
			new ScenarioCommand($this->plugin),
			new TpallCommand($this->plugin),
			new SpectateCommand($this->plugin),
			new LoginCommand($this->plugin),
			new RegisterCommand($this->plugin),
			new WorldCommand($this->plugin),
			new SetGroupCommand($this->plugin),
		]);
	}
}
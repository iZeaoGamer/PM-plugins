<?php
declare(strict_types=1);

namespace Core;

use Core\Config\ConfigLoader;
use Core\Economy\Economy;

class API{

	private $loader;

	public function __construct(CoreLoader $loader){
		$this->loader = $loader;
		$this->init();
	}

	public function init(){
		$this->getEconomy();
		$this->getServerConfig();
		$this->getEventListener();
	}

	/**
	 * @return Economy
	 */
	public function getEconomy() : Economy{
		$economy = new Economy($this->loader);
		return $economy;
	}

	/**
	 * @return ConfigLoader
	 */
	public function getServerConfig() : ConfigLoader{
		$config = new ConfigLoader($this->loader);

		return $config;
	}

	/**
	 * @return EventListener
	 */
	public function getEventListener() : EventListener{
		$listener = new EventListener($this->loader);

		return $listener;
	}

}
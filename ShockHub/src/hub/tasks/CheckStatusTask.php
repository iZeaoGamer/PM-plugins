<?php
declare(strict_types=1);

namespace hub\tasks;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class CheckStatusTask extends AsyncTask{

	public static $serverData = [];
	private $serverPort;

	public function __construct(string $serverPort){
		$this->serverPort = $serverPort;
	}

	public function onRun(){
		$status = file_get_contents("http://play.theshocknetwork.com/query/index.php?ip=play.theshocknetwork.com&port=" . $this->serverPort);

		if(strpos($status, "online") !== false){
			$playerCount = str_replace("online \n ", "", $status);
			$this->setResult("Online: " . $playerCount);
		}else{
			$this->setResult("Offline");
		}
	}

	public function onCompletion(Server $server){
		self::$serverData[$this->serverPort] = $this->getResult();
	}
}

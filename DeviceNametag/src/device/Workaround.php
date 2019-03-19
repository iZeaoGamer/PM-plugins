<?php
declare(strict_types=1);

namespace device;

use _64FF00\PureChat\PureChat;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\scheduler\PluginTask;

class Workaround extends PluginTask{

	private $plugin, $player;

	public function __construct(Loader $owner, Player $player){
		parent::__construct($owner, $player);
		$this->plugin = $owner;
		$this->player = $player;
	}

	public function onRun(int $currentTick){
		/** @var PureChat $pureChat */
		$pureChat = $this->plugin->getServer()->getPluginManager()->getPlugin("PureChat");
		$levelName = $pureChat->getConfig()->get("enable-multiworld-chat") ? $this->player->getLevel()->getName() : null;
		$nameTag = $pureChat->getNametag($this->player, $levelName);

		$this->player->setNameTag($this->plugin->convertOStoString($this->plugin->getDeviceOS()) . " " . $nameTag);
	}
}
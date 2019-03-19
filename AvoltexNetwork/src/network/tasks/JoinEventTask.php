<?php
declare(strict_types=1);

namespace network\tasks;

use network\NetworkCore;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\scheduler\PluginTask;

class JoinEventTask extends PluginTask{
	private $player;

	public function __construct(NetworkCore $plugin, Player $player){
		parent::__construct($plugin, $player);
		$this->player = $player;
	}

	public function onRun(int $currentTick){
		$this->player->playLevelEvent(LevelEventPacket::EVENT_GUARDIAN_CURSE);
	}
}
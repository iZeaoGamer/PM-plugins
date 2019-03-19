<?php
declare(strict_types=1);

/*
 * © AppleDevelops 2017
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author AppleDevelops
 *
*/

namespace network;

use network\customui\CustomUI;
use network\utils\Forms;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\ServerSettingsResponsePacket;
use pocketmine\network\mcpe\protocol\TransferPacket;
use pocketmine\Player;
use pocketmine\utils\Utils;

class AvoltexPlayer extends Player{

	public function playSound(string $soundName, float $volume = 100.0, float $pitch = 1.0){
		$pk = new PlaySoundPacket();
		$pk->soundName = $soundName;
		$pk->volume = $volume;
		$pk->pitch = $pitch;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$this->dataPacket($pk);
	}

	public function transferServer(string $address, int $port = 19132){
		$pk = new TransferPacket();
		$pk->address = $address;
		$pk->port = $port;
		$this->dataPacket($pk);
	}

	public function playLevelEvent(int $evid, int $data = 0){
		$pk = new LevelEventPacket();
		$pk->evid = $evid;
		$pk->position = $this->getPosition();
		$pk->data = $data;
		$this->dataPacket($pk);
	}
}
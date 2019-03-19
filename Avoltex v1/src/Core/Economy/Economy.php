<?php
declare(strict_types=1);

namespace Core\Economy;

use Core\CoreLoader;
use pocketmine\Player;
use pocketmine\utils\Config;

class Economy{

	private $loader;

	public function __construct(CoreLoader $loader){
		$this->loader = $loader;
	}

	/**
	 * @param Player $player
	 * @param int    $payout
	 */
	public function addCoins(Player $player, int $payout){
		$name = strtolower($player->getName());
		$data = new Config($this->loader->getDataFolder() . "players/$name.json", Config::JSON);

		$currentCoins = $data->get("coins");

		$data->set("coins", $currentCoins + $payout);

		$player->sendMessage("Added " . $payout . " coins to your account.");

		$data->save();
		$data->reload();
	}

	/**
	 * @param Player $player
	 *
	 * @return bool|mixed
	 */
	public function getCoins(Player $player){
		$name = strtolower($player->getName());
		$data = new Config($this->loader->getDataFolder() . "players/$name.json", Config::JSON);

		return $data->get("coins");
	}
}
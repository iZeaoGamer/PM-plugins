<?php
declare(strict_types=1);

namespace network\utils;

use network\AvoltexPlayer;
use network\interfaces\Groups;
use network\NetworkCore;
use pocketmine\Player;

class User{

	protected $group = "guest";
	/** @var \mysqli */
	protected $sql;

	public function __construct(){
		$this->sql = NetworkCore::getInstance()->sql;
	}

	public function sendLoginData(Player $player){
		$group = Groups::RANK_DEFAULT;
		$user = $player->getName();
		$prepare = $this->sql->prepare("INSERT IGNORE INTO players(username, usergroup) VALUES(?, ?)");
		$prepare->bind_param("ss", $user, $group);
		$prepare->execute();
		$prepare->close();
	}

	public function getGroup(Player $player){
		$prepare = $this->sql->prepare("SELECT usergroup FROM players WHERE username='{$player->getName()}'");;
		$prepare->bind_result($usergroup);
		$prepare->execute();
		$prepare->fetch();
		$prepare->close();

		return $usergroup;
	}

	public function setGroup(Player $player, string $group){
		$name = $player->getName();
		$prepare = $this->sql->prepare("UPDATE players SET usergroup=? WHERE username=?");
		$prepare->bind_param("ss", $group, $name);
		$prepare->execute();
		$prepare->close();
	}

	public function getNameTagFormat(Player $player){
		$group = $this->getGroup($player);
		$format = Groups::NAMETAG_FORMAT[$group];
		$format = str_replace("{display_name}", $player->getDisplayName(), $format);
		$format = str_replace("{name}", $player->getName(), $format);

		return $format;
	}

	public function getChatFormat(Player $player, string $message): string{
		$group = $this->getGroup($player);
		$format = Groups::CHAT_FORMAT[$group];
		$format = str_replace("{display_name}", $player->getDisplayName(), $format);
		$format = str_replace("{name}", $player->getName(), $format);
		$format = str_replace("{message}", $message, $format);

		return $format;
	}
}
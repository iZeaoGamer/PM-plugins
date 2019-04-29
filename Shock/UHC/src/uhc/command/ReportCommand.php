<?php

namespace uhc\command;

use pocketmine\command\utils\InvalidCommandSyntaxException;
use uhc\Loader;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\utils\TextFormat;

class ReportCommand extends PluginCommand{
	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		parent::__construct("report", $plugin);
		$this->plugin = $plugin;
		$this->setPermission("shock.staff.report");
		$this->setUsage("/report <playerName> <reason>");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
		if(count($args) < 2){
			throw new InvalidCommandSyntaxException();
		}

		$name = array_shift($args);
		$sname = $sender->getName();
		$player = $this->plugin->getServer()->getPlayer($name);
		if(!$player){
			$sender->sendMessage(TextFormat::GRAY . 'That player is not online!');

			return false;
		}
		if($sender === $player){
			$sender->sendMessage(TextFormat::GRAY . "You cannot report yourself!");

			return false;
		}else{
			$sender->sendMessage(TextFormat::GRAY . 'You have successfully reported ' . TextFormat::GOLD . $player->getName() . TextFormat::RESET . TextFormat::GRAY . ' for ' . implode(" ", $args));
			foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
				if(!$this->testPermission($player)){
					return true;
				}

				$player->sendMessage(TextFormat::RESET . TextFormat::GOLD . $sname . TextFormat::RESET . TextFormat::GRAY . ' has reported ' . TextFormat::GOLD . $player->getName() . TextFormat::RESET . TextFormat::GRAY . ' for ' . implode(" ", $args));
			}
		}

		return true;
	}
}
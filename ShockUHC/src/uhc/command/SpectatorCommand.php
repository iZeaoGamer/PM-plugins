<?php

namespace uhc\command;

use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\Player;
use uhc\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\utils\TextFormat;

class SpectatorCommand extends PluginCommand{
	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin){
		parent::__construct("spectate", $plugin);
		$this->plugin = $plugin;
		$this->setUsage("/spectate <playerName>");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
		if($sender instanceof Player){
			if($sender->getGamemode() === 3){
				if(isset($args[0])){
					$player = $this->plugin->getServer()->getPlayer(strtolower($args[0]));
					if($player !== null){
						if($player === $sender){
							$sender->sendMessage(TextFormat::RED . "You can't spectate yourself!");
						}else{
							$sender->teleport($player->getPosition());
							$sender->sendMessage(TextFormat::GREEN . "Now spectating:  " . $player->getDisplayName());
						}
					}else{
						$sender->sendMessage(TextFormat::RED . "That player is offline!");
					}
				}else{
					throw new InvalidCommandSyntaxException();
				}
			}else{
				$sender->sendMessage(TextFormat::RED . "You must be in spectator mode to use this command!");
			}
		}else{
			throw new InvalidCommandSyntaxException();
		}

		return true;
	}
}
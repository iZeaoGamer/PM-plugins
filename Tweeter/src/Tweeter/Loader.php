<?php
declare(strict_types=1);

namespace Tweeter;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\TranslationContainer;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

/**
 * Class Loader
 *
 * @package Tweeter
 */
class Loader extends PluginBase implements Listener {

	public $key, $secret, $token, $tokensecret, $twitter;
	public $host = "https://api.twitter.com/1.1/";
	public $prefix = TextFormat::AQUA . "[Tweeter] " . TextFormat::RED;

	public function onEnable(){
		if(!file_exists($this->getDataFolder())){
			mkdir($this->getDataFolder());
		}
		$this->saveDefaultConfig();

		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	/**
	 * @return string
	 */
	public function getPrefix(){
		return $this->prefix;
	}


	/**
	 * @param CommandSender $sender
	 * @param Command       $cmd
	 * @param string        $label
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
		parent::onCommand($sender, $cmd, $label, $args);
		if($sender->hasPermission("tweeter.tweet")){
			switch($cmd->getName()){
				case "tweeter":
					switch(array_shift($args)){
						case "tweet":
							$this->postTweet($sender, implode(" ", $args));
							break;

						case "dm":
							$this->sendDM($sender, array_shift($args), implode(" ", $args));
							break;
					}
			}
		}else{
			$sender->sendMessage(new TranslationContainer(TextFormat::RED . "commands.generic.perimission"));
		}

		return false;
	}

	public function getOAuth(){
		require_once("twitteroauth.php");

		$config = $this->getConfig();
		$this->key = $config->get("key");
		$this->secret = $config->get("secret");
		$this->token = $config->get("token");
		$this->tokensecret = $config->get("tokensecret");

		$this->twitter = new \TwitterOAuth($this->key, $this->secret, $this->token, $this->tokensecret);
		$this->twitter->host = $this->host;
	}

	/**
	 * @param CommandSender $sender
	 * @param               $tweet
	 */
	public function postTweet(CommandSender $sender, $tweet){
		$this->getOAuth();
		if(strlen($tweet) > 140){
			$sender->sendMessage($this->getPrefix()."The tweet is larger than 140 characters, failed to send.");
		}else{
			/** @noinspection PhpUndefinedMethodInspection */
			$this->twitter->post('statuses/update', ['status' => $tweet]);
			$sender->sendMessage($this->getPrefix() . "Tweet sent!");
		}
	}

	/**
	 * @param CommandSender $sender
	 * @param               $user
	 * @param               $message
	 */
	public function sendDM(CommandSender $sender, $user, $message){
		$this->getOAuth();
		if(strlen($message) > 10000){
			$sender->sendMessage($this->getPrefix()."DM is larger than 10,000 characters, failed to send.");
		}else{
			/** @noinspection PhpUndefinedMethodInspection */
			$this->twitter->post('direct_messages/new', ['screen_name' => $user, 'text' => $message]);
			$sender->sendMessage($this->getPrefix() . "DM sent!");
		}
	}
}
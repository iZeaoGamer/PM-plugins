<?php

declare(strict_types=1);

namespace Adrenaline\TwitterAPI;

use Adrenaline\Loader;

/**
 * Class TwitterManager
 *
 * @package Adrenaline\TwitterAPI
 */
class TwitterManager {

	public $plugin;

	/**
	 * TwitterManager constructor.
	 *
	 * @param Loader $loader
	 */
	public function __construct(Loader $loader){
		$this->plugin = $loader;
	}

	/**
	 * @param $tweet
	 */
	public function postTweet($tweet){
		require_once('twitteroauth.php');

		$key = '0N1EgSQguhKTirCQFuIIsboj1';
		$secret = 'SfVP6c0P08uYA9nHMTtRHRdwVQBmjWkgZZpB4LtVhitw9khLht';
		$token = '732693923022446592-fUrOK3qJvNagwIzhIrM03TpOSMphe6c';
		$asecret = 'mRVV1aoJRLEJBUNghL1rvaX3g2O9cYnNedptRPhqvwdpl';

		$twitter = new \TwitterOAuth($key, $secret, $token, $asecret);
		$twitter->host = "https://api.twitter.com/1.1/";
		if(strlen($tweet) > 140){
			$this->plugin->getLogger()->alert("The tweet couldn't be sent because it is longer than 140 characters");
		}else{
			$twitter->post('statuses/update', ['status' => $tweet]);
		}
	}
}
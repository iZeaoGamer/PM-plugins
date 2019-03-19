<?php
declare(strict_types=1);

namespace network\interfaces;

interface Groups{
	const RANK_DEFAULT = 'guest';

	const CHAT_FORMAT = [
		"guest"   => "{display_name} > {message}",
		"admin"   => "§l§6ADMIN §r§7{display_name} §6 > §7{message}",
		"owner"   => "§l§4OWNER §r§7{display_name} §4> §7{message}",
		"youtube" => "§l§cYou§7Tube §r§7{display_name} §c > §7{message}",
		"famous"  => "§l§2FAMOUS §r§7{display_name} §b > §7{message}"
	];
	const NAMETAG_FORMAT = [
		"guest" => "",
		"admin" => ""
	];
	const PERMISSIONS = [
		"guest" => [

		],

		"admin" => [

		],

		"owner" => [

		]
	];
	const GROUPS = [
		'guest',
		'admin',
		'owner'
	];
}
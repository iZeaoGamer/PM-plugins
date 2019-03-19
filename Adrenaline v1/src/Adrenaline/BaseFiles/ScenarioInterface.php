<?php
declare(strict_types=1);

namespace Adrenaline\BaseFiles;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\inventory\FurnaceBurnEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\server\DataPacketReceiveEvent;

/**
 * Interface ScenarioInterface
 *
 * @package Adrenaline\BaseFiles
 */
interface ScenarioInterface {

	/**
	 * @param EntityDamageEvent $event
	 *
	 * @return mixed
	 */
	public function onDamage(EntityDamageEvent $event);

	/**
	 * @param PlayerDeathEvent $event
	 *
	 * @return mixed
	 */
	public function onDeath(PlayerDeathEvent $event);

	/**
	 * @param BlockBreakEvent $event
	 *
	 * @return mixed
	 */
	public function onBreak(BlockBreakEvent $event);

	/**
	 * @param EntityDeathEvent $event
	 *
	 * @return mixed
	 */
	public function onEntityDeath(EntityDeathEvent $event);

	/**
	 * @param CraftItemEvent $event
	 *
	 * @return mixed
	 */
	public function onCraft(CraftItemEvent $event);

	/**
	 * @param FurnaceBurnEvent $event
	 *
	 * @return mixed
	 */
	public function onBurn(FurnaceBurnEvent $event);

	/**
	 * @param DataPacketReceiveEvent $event
	 *
	 * @return mixed
	 */
	public function onDataRecieve(DataPacketReceiveEvent $event);

}
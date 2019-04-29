<?php

declare(strict_types=1);

namespace uhc;

use network\NetworkPlayer;

class UHCPlayer extends NetworkPlayer{

	private $hasNoClean = false;
	private $noCleanTime = 0;

	public function hasNoClean() : bool{
		return $this->hasNoClean;
	}

	public function setNoCleanActive(bool $noClean){
		$this->hasNoClean = $noClean;
	}

	public function getNoCleanTime() : int{
		return $this->noCleanTime;
	}

	public function setNoCleanTime(int $time){
		$this->noCleanTime = $time;
	}
}
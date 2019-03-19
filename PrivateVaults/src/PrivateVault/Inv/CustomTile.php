<?php

namespace PrivateVault\Inv;

use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\tile\Chest;

class CustomTile extends Chest{

    public function __construct(Level $level, CompoundTag $nbt){
        parent::__construct($level, $nbt);
        $this->inventory = new CustomInv($this);
    }

    public function getReplacement() : Block{
        $replace = $this->namedtag->replace->getValue() ?? [0, 0];
        return Block::get($replace[0], $replace[1]);
    }
}
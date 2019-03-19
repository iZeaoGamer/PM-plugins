<?php

namespace PrivateVault\Inv;

use pocketmine\inventory\ChestInventory;
use pocketmine\inventory\InventoryType;
use pocketmine\Player;

class CustomInv extends ChestInventory{

    public function __construct(CustomTile $tile){
        parent::__construct($tile, InventoryType::get(InventoryType::CHEST));
    }

    public function onOpen(Player $who){
        parent::onOpen($who);
    }

    public function onClose(Player $who){
        $pos = $this->holder;
        $block = $pos->getReplacement();
        $block->x = floor($pos->x);
        $block->y = floor($pos->y);
        $block->z = floor($pos->z);
        $block->level = $pos->getLevel();
        if($who instanceof Player) $block->level->sendBlocks([$who], [$block]);
        parent::onClose($who);
        $this->holder->close();
    }
}
<?php

namespace Core\Tasks;

use Core\Loader;
use pocketmine\scheduler\PluginTask;
use pocketmine\utils\TextFormat;

class Restart extends PluginTask {

    public $restart = 1200;

    public function __construct(Loader $owner) {
        parent::__construct($owner);
        $this->plugin = $owner;
    }

    public function onRun($task) {
        $this->restart--;
        $this->owner->getServer()->broadcastPopup(TextFormat::RED . "Server will restart in " . gmdate("i:s", $this->restart) . "\n");
        if ($this->restart === 0) {
            $this->owner->getServer()->shutdown();
            foreach ($this->owner->getServer()->getOnlinePlayers() as $p) {
                $p->kick("Server restart");
            }
        }
    }

}

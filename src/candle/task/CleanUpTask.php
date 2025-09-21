<?php

namespace candle\task;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\player\Player;
use candle\Loader;

class CleanUpTask extends Task
{

    public function onRun(): void
    {
        foreach (Loader::getInstance()->inCombat as $playerName => $data) {
            if ($data["time"] <= time()) {
                unset(Loader::getInstance()->inCombat[$playerName]);

                if (Loader::getInstance()->getConfig()->get("hidePlayers", false)) {
                    $player = Server::getInstance()->getPlayerExact($playerName);
                    if ($player instanceof Player) {
                        $this->showAll($player);
                    }
                }
            }
        }
    }

    private function showAll(Player $player): void
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $online) {
            $player->showPlayer($online);
        }
    }
}

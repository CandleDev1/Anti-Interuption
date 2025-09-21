<?php

namespace candle;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\player\Player;
use pocketmine\Server;

class EventListener implements Listener
{

    public function __construct(private Loader $loader) {}

    public function event(EntityDamageEvent $event): void
    {
        $player = $event->getEntity();
        if (!$player instanceof Player) return;

        if ($event instanceof EntityDamageByEntityEvent) {
            $attacker = $event->getDamager();
            if (!$attacker instanceof Player) return;
            if ($player->getName() === $attacker->getName()) return;

            if (isset(Loader::getInstance()->inCombat[$attacker->getName()])) {
                $opponent = Loader::getInstance()->inCombat[$attacker->getName()]["opponent"];
                if ($opponent !== $player->getName() && Loader::getInstance()->inCombat[$attacker->getName()]["time"] > time()) {
                    $attacker->sendMessage($this->loader->getConfig()->get("message"));
                    $event->cancel();
                    return;
                }
            }

            if (isset(Loader::getInstance()->inCombat[$player->getName()])) {
                $opponent = Loader::getInstance()->inCombat[$player->getName()]["opponent"];
                if ($opponent !== $attacker->getName() && Loader::getInstance()->inCombat[$player->getName()]["time"] > time()) {
                    $attacker->sendMessage($this->loader->getConfig()->get("message"));
                    $event->cancel();
                    return;
                }
            }

            Loader::getInstance()->inCombat[$player->getName()] = [
                "opponent" => $attacker->getName(),
                "time" => time() + $this->loader->getConfig()->get("combatTime")
            ];
            Loader::getInstance()->inCombat[$attacker->getName()] = [
                "opponent" => $player->getName(),
                "time" => time() + $this->loader->getConfig()->get("combatTime")
            ];

            if ($this->loader->getConfig()->get("hidePlayers", false)) {
                $this->hideOthers($attacker, $player);
                $this->hideOthers($player, $attacker);
            }
        }
    }

    public function playerDeathEvent(PlayerDeathEvent $event): void
    {
        $player = $event->getPlayer();
        $name = $player->getName();

        if (isset(Loader::getInstance()->inCombat[$name])) {
            $opponentName = Loader::getInstance()->inCombat[$name]["opponent"] ?? null;

            unset(Loader::getInstance()->inCombat[$name]);

            if ($opponentName !== null && isset(Loader::getInstance()->inCombat[$opponentName])) {
                unset(Loader::getInstance()->inCombat[$opponentName]);

                if ($this->loader->getConfig()->get("hidePlayers", false)) {
                    $opponent = Server::getInstance()->getPlayerExact($opponentName);
                    if ($opponent instanceof Player) {
                        $this->showAll($opponent);
                    }
                }
            }
        }

        if ($this->loader->getConfig()->get("hidePlayers", false)) {
            $this->showAll($player);
        }
    }

    private function hideOthers(Player $player, Player $opponent): void
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $online) {
            if ($online->getName() !== $player->getName() && $online->getName() !== $opponent->getName()) {
                $player->hidePlayer($online);
            } else {
                $player->showPlayer($online);
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

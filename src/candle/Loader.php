<?php

namespace candle;

use candle\task\CleanUpTask;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class Loader extends PluginBase
{

    use SingletonTrait;

    public array $inCombat = [];


    public function onLoad(): void
    {
        self::setInstance($this);
    }

    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getScheduler()->scheduleRepeatingTask(new CleanUpTask(), 20);
    }


    public static function getInstance(): Loader
    {
        return self::$instance;
    }
}

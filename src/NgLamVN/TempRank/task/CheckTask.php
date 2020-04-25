<?php

namespace NgLamVN\TempRank\task;

use pocketmine\scheduler\Task;
use NgLamVN\TempRank\TempRank;

class CheckTask extends Task
{
    private $plugin;

    public function __construct(TempRank $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick)
    {
        foreach (array_keys($this->plugin->getData()) as $player)
        {
            $this->plugin->reduceTime($player, 1);
            if ($this->plugin->getTime($player) <= 0)
            {
                $this->plugin->removeRank($player);
                $this->plugin->resetTime($player);
                if ($this->plugin->getServer()->getPlayer($player) !== null)
                {
                    $this->plugin->getServer()->getPlayer($player)->sendMessage("VIP của bạn đã hến hạn, vui lòng mua lại gói vip mới :)");
                }
            }
        }
    }
}

<?php

/**
 *  Create and coding by NgLamVN
 *  github.com/LamPocketVN
 *  idea by ThinkerS
 *  github.com/ThinkerS2k18
 */

namespace NgLamVN\TempRank;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\Server;

use NgLamVN\TempRank\command\SetRank;
use NgLamVN\TempRank\command\RemoveRank;
use NgLamVN\TempRank\task\CheckTask;

use _64FF00\PurePerms\PurePerms;

class TempRank extends PluginBase
{
    public static $instance;

    public $config;

    public $data;

    public $pp;

    public function protect()
    {
        $plugin = $this->getPluginLoader()->getPluginDescription();
    }

    public function onLoad()
    {
        self::$instance = $this;
    }

    public function onEnable()
    {
        $this->pp = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
        if ($this->pp == null)
        {
            $this->getLogger()->alert("PurePerms plugin are requied !!");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }

        $this->getServer()->getCommandMap()->register("setrank", new SetRank($this));
        $this->getServer()->getCommandMap()->register("removerank", new RemoveRank($this));

        if (!file_exists($this->getDataFolder()))
        {
            mkdir($this->getDataFolder());
        }

        $this->config = new Config($this->getDataFolder() .  "times.yml", Config::YAML);

        $this->getScheduler()->scheduleRepeatingTask(new CheckTask($this), 20);
    }
    public function onDisable()
    {
        $this->config->save();
    }

    /**
     *  API >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
     */
    public static function getTempRank(): ?TempRank
    {
        return self::$instance;
    }

    public function getData()
    {
        return $this->config->getAll();
    }

    public function getTime ($player)
    {
        if ($player instanceof Player)
        {
            if ($this->config->getNested($player->getName()) !== null)
            {
                return $this->config->getNested($player->getName());
            }
            return 0;
        }
        if ($this->config->getNested($player) !== null)
        {
            return $this->config->getNested($player);
        }
        return 0;
    }

    public function setTime ($player, $time)
    {
        if ($player instanceof Player)
        {
            $this->config->setNested($player->getName(), $time);
            $this->config->save();
            return;
        }
        $this->config->setNested($player, $time);
        $this->config->save();
    }

    public function addTime ($player, $time)
    {
        $this->setTime($player, $this->getTime($player) + $time);
    }

    public function reduceTime ($player, $time)
    {
        $this->setTime($player, $this->getTime($player) - $time);
    }

    public function resetTime ($player)
    {
        if ($player instanceof Player)
        {
            $this->config->remove($player->getName());
            $this->config->save();
            return;
        }
        $this->config->remove($player);
        $this->config->save();
    }

    public function removeRank ($player)
    {
        if (!($player instanceof Player))
        {
            $player = $this->getServer()->getOfflinePlayer($player);
        }
        $this->pp->setGroup($player, $this->pp->getDefaultGroup());
    }

    public function addRank ($player, $rank): bool
    {
        if (!($player instanceof Player))
        {
            $player = $this->getServer()->getOfflinePlayer($player);
        }
        $group = $this->pp->getGroup($rank);
        if ($group == null)
        {
            return false;
        }
        $this->pp->setGroup($player, $group);
        return true;
    }

    public function TimeConvent ($time) // For Easy Use
    {
        $day = floor($time / 86400);
        $hour = floor(($time - $day * 86400) / 3600);
        $min = floor(($time - $day*86400 - $hour*3600) / 60);
        $sec = $time - $day*86400 - $hour*3600 - $min*60;
        return [$day, $hour, $min, $sec];
    }

    public function ToSecond ($day, $hour, $min, $sec) // For Easy Use
    {
        return $day*86400 + $hour+3600 + $min*60 + $sec;
    }
}
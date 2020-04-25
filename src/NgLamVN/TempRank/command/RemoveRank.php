<?php

namespace NgLamVN\TempRank\command;

use pocketmine\command\{CommandSender, Command, PluginCommand};

use NgLamVN\TempRank\TempRank;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class RemoveRank extends PluginCommand
{
    private $plugin;

    public function __construct(TempRank $plugin)
    {
        parent::__construct("removerank", $plugin);
        $this->plugin = $plugin;
        $this->setPermission("tr.removerank");
        $this->setDescription("REMOVE RANK");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission("tr.removerank"))
        {
            $sender->sendMessage("Bạn không có quyền sử dụng lệnh này !");
            return;
        }
        if (!isset($args[0]))
        {
            $sender->sendMessage("/removerank <player>");
            return;
        }
        $player = $this->plugin->getServer()->getPlayer($args[0]);
        if ($player === null)
        {
            if ($this->plugin->getTime($args[0]) >= 0)
            {
                $player = $args[0];
            }
            else
            {
                $sender->sendMessage("Player không online tồn tại !");
                return;
            }
        }
        $this->plugin->removeRank($player);
        $this->plugin->resetTime($player);
        if ($player instanceof Player)
        {
            $sender->sendMessage("Xoá rank thành công cho " . $player->getName());
        }
        else
        {
            $sender->sendMessage("Xoá rank thành công cho " . $player);
        }
    }
}
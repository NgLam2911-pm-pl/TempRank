<?php

namespace NgLamVN\TempRank\command;

use pocketmine\command\{PluginCommand, CommandSender, Command};

use NgLamVN\TempRank\TempRank;
use pocketmine\plugin\Plugin;

class SetRank extends PluginCommand
{
    private $plugin;

    public function __construct(TempRank $plugin)
    {
        parent::__construct("setrank", $plugin);
        $this->plugin = $plugin;
        $this->setPermission("tr.setrank");
        $this->setDescription("SET RANK");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission("tr.setrank"))
        {
            $sender->sendMessage("Bạn không có quyền sử dụng lệnh này.");
            return;
        }
        if (!isset($args[0]))
        {
            $sender->sendMessage("/setrank <player> <rank> <days>");
            return;
        }
        if (!isset($args[1]))
        {
            $sender->sendMessage("/setrank <player> <rank> <days>");
            return;
        }
        if (!isset($args[2]))
        {
            $sender->sendMessage("/setrank <player> <rank> <days>");
            return;
        }
        if (!is_numeric($args[2]))
        {
            $sender->sendMessage("thời gian phải là số !");
            return;
        }
        $player = $this->plugin->getServer()->getPlayer($args[0]);
        if ($player === null)
        {
            $sender->sendMessage("Player không online hoặc không tồn tại");
        }

        $rank = $args[1];
        $time = $args[2];
        if ($this->plugin->addRank($player, $rank))
        {
            $this->plugin->setTime($player, $time * 86400);
            $sender->sendMessage("Set rank " . $rank . " thành công cho " . $player->getName() . " trong " . $time . " ngày !");
        }
        else
        {
            $sender->sendMessage("Rank không tồn tại.");
        }
    }
}

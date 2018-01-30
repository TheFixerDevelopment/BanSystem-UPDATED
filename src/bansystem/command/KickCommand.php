<?php

namespace bansystem\command;

use bansystem\translation\Translation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class KickCommand extends Command {
    
    public function __construct() {
        parent::__construct("kick");
        $this->description = "Removes the given player.";
        $this->usageMessage = "/kick <player> [reason...]";
        $this->setPermission("bansystem.command.kick");
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if ($this->testPermissionSilent($sender)) {
            if (count($args) <= 0) {
                $sender->sendMessage(Translation::translateParams("usage", array($this)));
                return false;
            }
            $player = $sender->getServer()->getPlayer($args[0]);
            if (count($args) == 1) {
                if ($player != null) {
                    $player->kick(TextFormat::RED . "You have been kicked from our network\§4kicked by: §b$sender->getName()\n§5Reason: Not provided.", false);
                    $sender->getServer()->broadcastMessage(TextFormat::AQUA . $player->getName() . TextFormat::RED . " has been kicked from our network!\n§4Kicked by: §b$sender->getName()");
                } else {
                    $sender->sendMessage(Translation::translate("playerNotFound"));
                }
            } else if (count($args) >= 2) {
                if ($player != null) {
                    $reason = "";
                    for ($i = 1; $i < count($args); $i++) {
                        $reason .= $args[$i];
                        $reason .= " ";
                    }
                    $reason = substr($reason, 0, strlen($reason) - 1);
                    $player->kick(TextFormat::RED . "You have been kicked from our network\n§4Kicked by: §b$sender->getName()\n§5Reason: " . TextFormat::AQUA . $reason . TextFormat::RED . "\n§6Did you get banned unfairly? §5Please appeal your ban! §3http://tinyurl.com/vmpebanappeal", false);
                    $sender->getServer()->broadcastMessage(TextFormat::AQUA . $player->getName() . TextFormat::RED . " has been kicked from our network\n§4Kicked by: §b$sender->getName()\n§5Reason: " . TextFormat::AQUA . $reason . TextFormat::RED . "\n§6Did you get banned unfairly? §5Please appeal your ban! §3http://tinyurl.com/vmpebanappeal");
                } else {
                    $sender->sendMessage(Translation::translate("playerNotFound"));
                }
            }
        } else {
            $sender->sendMessage(Translation::translate("noPermission"));
        }
        return true;
    }
}

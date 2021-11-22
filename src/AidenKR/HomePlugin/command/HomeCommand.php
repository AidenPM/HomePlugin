<?php

namespace AidenKR\HomePlugin\command;

use AidenKR\HomePlugin\HomePlugin;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class HomeCommand extends Command
{
    /** @var HomePlugin */
    protected HomePlugin $plugin;

    public function __construct(HomePlugin $plugin)
    {
        parent::__construct("home", "This is Home Command");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $player = $sender;

        if($player instanceof Player){
            $this->plugin->moveHome($player);
            $player->sendMessage(HomePlugin::$prefix . "teleport your home.");
        }
    }
}

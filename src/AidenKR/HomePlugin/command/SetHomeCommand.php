<?php

namespace AidenKR\HomePlugin\command;

use AidenKR\HomePlugin\HomePlugin;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;


class SetHomeCommand extends Command
{
    /** @var HomePlugin */
    protected HomePlugin $plugin;

    public function __construct(HomePlugin $plugin)
    {
        parent::__construct("sethome", "This is Set Home Command");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $player = $sender;

        if($player instanceof Player){
            $this->plugin->setHome($player);
            $player->sendMessage(HomePlugin::$prefix . "Home is Set.");
        }
    }
}

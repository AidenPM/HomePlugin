<?php

namespace AidenKR\HomePlugin\listener;

use AidenKR\HomePlugin\HomePlugin;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class EventListener implements Listener
{
    /** @var HomePlugin */
    protected HomePlugin $plugin;

    public function __construct(HomePlugin $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();

        if(!isset($this->plugin->data[$player->getName()])){
            $this->plugin->data[$player->getName()] = [
                "x" => null,
                "y" => null,
                "z" => null,
                "world" => null
            ];
        }
    }
}

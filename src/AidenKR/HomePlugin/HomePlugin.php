<?php

namespace AidenKR\HomePlugin;

use AidenKR\HomePlugin\command\HomeCommand;
use AidenKR\HomePlugin\command\SetHomeCommand;
use AidenKR\HomePlugin\listener\EventListener;
use pocketmine\entity\Location;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\Position;

class HomePlugin extends PluginBase
{
    use SingletonTrait;

    public static string $prefix = '§l§a[!] §r§7';

    public array $data = [];

    protected function onLoad(): void
    {
        self::setInstance($this);
    }

    protected function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

        $this->getServer()->getCommandMap()->registerAll('AidenKR', [
            new HomeCommand($this), new SetHomeCommand($this)
        ]);

        if (!file_exists($this->getDataFolder() . "data.json")) {
            $this->data = json_decode(file_get_contents($this->getDataFolder() . "data.json"), true);
        }
    }

    public function moveHome(Player $player)
    {
        $pos = new Position(
            $this->data[$player->getName()]["x"],
            $this->data[$player->getName()]["y"],
            $this->data[$player->getName()]["z"],
            $this->data[$player->getName()]["world"]
        );
        $player->teleport($pos);
    }

    public function setHome(Player $player)
    {
        $this->data[$player->getName()]["x"] = $player->getPosition()->getFloorX();
        $this->data[$player->getName()]["y"] = $player->getPosition()->getFloorY();
        $this->data[$player->getName()]["z"] = $player->getPosition()->getFloorZ();
        $this->data[$player->getName()]["world"] = $player->getPosition()->getWorld();
    }

    protected function onDisable(): void
    {
        file_put_contents($this->getDataFolder() . "data.json", json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

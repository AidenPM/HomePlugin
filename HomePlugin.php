<?php

/**
 * @name HomePlugin
 * @author pju6791
 * @version 3.0.0
 * @api 3.0.0
 * @main pju6791\HomePlugin\HomePlugin
 */

namespace pju6791\HomePlugin;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class HomePlugin extends PluginBase
{

    public $prefix = '§l§b[HomePlugin] §r§7';

    public $home, $db;

    public function onEnable()
    {

        $this->getServer()->getCommandMap()->register("home", new PluginCommand("home", $this));
        $this->getServer()->getCommandMap()->register("sethome", new PluginCommand("sethome", $this));

        $this->getServer()->getPluginManager()->registerEvents(new class implements Listener {

            public HomePlugin $plugin;

            public function __construct(HomePlugin $plugin) {
                $this->plugin = $plugin;
            }

            public function onJoin(PlayerJoinEvent $event) {

                $player = $event->getPlayer();

                if(!isset($this->plugin->db[$player->getName()])) {
                    $this->plugin->db[$player->getName()]["x"] = null;
                    $this->plugin->db[$player->getName()]["y"] = null;
                    $this->plugin->db[$player->getName()]["z"] = null;
                    $this->plugin->db[$player->getName()]["world"] = null;
                }
            }
        }, $this);

        $this->home = new Config($this->getDataFolder() . 'config.json', Config::JSON);
        $this->db = $this->home->getAll();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        $player = $sender;

        if($player instanceof Player) {
            if($command->getName() === "home") {
                $pos = new Position(
                    $this->db[$player->getName()]["x"],
                    $this->db[$player->getName()]["y"],
                    $this->db[$player->getName()]["z"],
                    $this->db[$player->getName()]["world"]
                );
                $player->teleport($pos);

            } elseif ($command->getName() === "sethome") {
                $this->db[$player->getName()]["x"] = $player->getFloorX();
                $this->db[$player->getName()]["y"] = $player->getFloorY();
                $this->db[$player->getName()]["z"] = $player->getFloorZ();
                $this->db[$player->getName()]["world"] = $player->getLevel()->getFolderName();
                $this->onSave();
                $player->sendMessage($this->prefix . "The house is set.");
                $player->sendMessage($this->prefix . "infermation : {$player->getFloorX()} : {$player->getFloorY()} : {$player->getFloorZ()} : {$player->getLevel()->getFolderName()}");
            }
        }
        return true;
    }

    public function onSave(): ?Config
    {
        if ($this->home instanceof Config) {
            $this->home->setAll($this->db);
            $this->home->save();
        }
        return $this->home;
    }
}

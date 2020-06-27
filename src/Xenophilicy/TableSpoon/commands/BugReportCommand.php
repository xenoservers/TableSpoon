<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\commands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\Player;
use pocketmine\Server as PMServer;
use Xenophilicy\TableSpoon\TableSpoon;

/**
 * Class BugReportCommand
 * @package Xenophilicy\TableSpoon\commands
 */
class BugReportCommand extends VanillaCommand {
    /**
     * BugReportCommand constructor.
     * @param $name
     */
    public function __construct($name){
        parent::__construct($name, "Dumps parse-able information for Bug / Issue Report");
        $this->setPermission("TableSpoon.command.bugreport");
    }
    
    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return mixed|void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if($sender instanceof Player){
            $sender->sendMessage("This command must be ran using the server's console.");
            return;
        }
        $sender->sendMessage("Dumping Server Information...");
        $str = "";
        $str .= "Server Version: " . $sender->getServer()->getName() . " " . $sender->getServer()->getPocketMineVersion() . "\n";
        $str .= "API Version: " . $sender->getServer()->getApiVersion() . "\n";
        $str .= "Minecraft Version: " . $sender->getServer()->getVersion() . "\n";
        $str .= "Protocol Version: " . ProtocolInfo::CURRENT_PROTOCOL . "\n";
        $str .= "PHP Version: " . PHP_VERSION . "\n";
        $str .= "Host info: " . php_uname("a") . "\n";
        $pstr = "";
        $pstr .= "Plugins: ";
        foreach($sender->getServer()->getPluginManager()->getPlugins() as $pl){
            $pstr .= $pl->getDescription()->getFullName() . ", ";
        }
        $pstr = substr($pstr, 0, -2);
        $str .= $pstr . "\n";
        $str .= "Base64 Encoded Config: " . $this->encodeFile(TableSpoon::getInstance()->getDataFolder() . "config.yml") . "\n";
        $str .= "Base64 Encoded PocketMine Configuration: " . $this->encodeFile(PMServer::getInstance()->getDataPath() . "pocketmine.yml") . "\n";
        $str .= "Base64 Encoded Server Properties: " . $this->encodeFile(PMServer::getInstance()->getDataPath() . "server.properties") . "\n";
        $str .= "Base64 Encoded TSP CACHE " . $this->encodeFile(TableSpoon::getInstance()->getDataFolder() . "cache.json") . "\n";
    
        if(!is_dir(TableSpoon::getInstance()->getDataFolder() . "dumps")){
            mkdir(TableSpoon::getInstance()->getDataFolder() . "dumps");
        }
        $fn = TableSpoon::getInstance()->getDataFolder() . "dumps/TableSpoonDump_" . date("M_j_Y-H.i.s", time()) . ".txt";
        file_put_contents($fn, "TableSpoon Dump " . date("D M j H:i:s T Y", time()) . "\n", FILE_APPEND);
        file_put_contents($fn, "=== BEGIN BASE64 ENCODED DUMP ===\n", FILE_APPEND);
        file_put_contents($fn, wordwrap(base64_encode($str), 75, "\n", true) . "\n", FILE_APPEND);
        file_put_contents($fn, "=== END OF BASE64 ENCODED DUMP ===", FILE_APPEND);
        $sender->sendMessage("Saved to: " . $fn);
    }
    
    private function encodeFile(string $filePath): string{
        return base64_encode(file_get_contents($filePath));
    }
}

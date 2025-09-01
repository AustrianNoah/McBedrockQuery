<?php

namespace AusiPlayz\Query;

use austriannoah\mcbedrockquery\exception\QueryException;
use austriannoah\mcbedrockquery\QueryClient;
use pocketmine\plugin\PluginBase;

class ExampleCode extends PluginBase {


    public function onEnable(): void
    {
        $this->querySomeServer();
    }

    private function querySomeServer(): void
    {
        $queryClient = new QueryClient();
        try {
            $data = $queryClient->queryServer("geo.hivebedrock.network", 19132);
            $msg = "§aServer:\n";
            $msg .= "§eMOTD: §f" . $data["motd"] . "\n";
            $msg .= "§bVersion: §f" . $data["version"] . "\n";
            $msg .= "§dPlayers: §f" . $data["onlinePlayers"] . "/" . $data["maxPlayers"] . "\n";
            $msg .= "§6Gamemode: §f" . $data["gamemode"] . "\n";
            $this->getLogger()->info($msg);
        } catch (QueryException $exception) {
            $this->getLogger()->error($exception->getMessage());
        }
    }
}
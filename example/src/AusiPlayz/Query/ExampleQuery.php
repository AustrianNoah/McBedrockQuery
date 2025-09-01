<?php

namespace AusiPlayz\Query;

use austriannoah\mcbedrockquery\exception\QueryException;
use austriannoah\mcbedrockquery\QueryClient;
use austriannoah\mcbedrockquery\utils\QueryUtil;
use pocketmine\plugin\PluginBase;

class ExampleCode extends PluginBase {


    public function onEnable(): void
    {
        $this->querySomeServer();
    }

    private function querySomeServer(): void
    {
        try {
            $query = new QueryClient();
            $query->queryServer("geo.hivebedrock.network", 19132);
            $simpleMotd = QueryUtil::simpleMotd("geo.hivebedrock.network");
            $this->getLogger()->info($simpleMotd);
        } catch (QueryException $exception) {
            $this->getLogger()->error($exception->getMessage());
        }
    }
}
<?php
declare(strict_types=1);

namespace austriannoah\mcbedrockquery\utils;

use austriannoah\mcbedrockquery\QueryClient;
use MongoDB\Driver\Query;

final class QueryUtil {

    /**
     * Help function to request faster
     *
     * @param string $host
     * @param int $port
     * @return string
     */
    public static function simpleMotd(string $host, int $port = 19132): string
    {
        $client = new QueryClient();
        $data = $client->queryServer($host, $port);
        return $data["motd"];
    }

}
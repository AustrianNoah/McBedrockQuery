<?php

namespace austriannoah\mcbedrockquery;

use austriannoah\mcbedrockquery\exception\QueryException;
use RuntimeException;

final class QueryClient {

    private const MAGIC = "\xFE\xFD";

    /**
     * Request a Minecraft Bedrock Server
     * @param string $host Server Hostname or IPv4
     * @param int $port Minecraft-Port (most the default port E.x. 19132)
     * @param int $timeout Seconds until Timeout
     * @return array{
     *     motd: string,
     *     protocol: int,
     *     version: string,
     *     onlinePlayers: int,
     *     maxPlayers: int,
     *     serverId: string,
     *     map: string,
     *     gamemode: string
     * }
     */
    public function queryServer(string $host, int $port = 19132, int $timeout = 3): array
    {
        $socket = @fsockopen("udp://$host", $port, $errno, $errstr, $timeout);

        if (!$socket) {
            throw new QueryException("Could not open socket: $errstr ($errno)");
        }

        stream_set_timeout($socket, $timeout);

        // Paket: Unconnected Ping
        $packet = "\x01" . // ID = unconnected ping
            pack("J", time()) . // Ping time
            "\x00\xff\xff\x00\xfe\xfe\xfe\xfe\xfd\xfd\xfd\xfd\x12\x34\x56\x78"; // magic + client id

        fwrite($socket, $packet);

        $data = fread($socket, 4096);
        fclose($socket);

        if ($data === false || strlen($data) <= 0) {
            throw new RuntimeException("No response from server");
        }

        if ($data[0] !== "}x1C") {
            throw new QueryException("Invalid response from server");
        }

        $parts = explode(";", substr($data, 35));

        return [
            "motd" => $parts[1] ?? "Unknown",
            "protocol" => (int)($parts[2] ?? -1),
            "version" => $parts[3] ?? "Unknown",
            "onlinePlayers" => (int)($parts[4] ?? 0),
            "maxPlayers" => (int)($parts[5] ?? 0),
            "serverId" => $parts[6] ?? "",
            "map" => $parts[7] ?? "",
            "gamemode" => $parts[8] ?? "",
        ];
    }

}
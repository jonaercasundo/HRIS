<?php

class ZKLib
{
    private $ip;
    private $port;
    private $socket;

    public function __construct($ip, $port = 4370)
    {
        $this->ip = $ip;
        $this->port = $port;
    }

    public function connect()
    {
        $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

        if (!$this->socket) {
            throw new Exception("Cannot create socket");
        }

        socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, [
            "sec" => 5,
            "usec" => 0
        ]);

        return true;
    }

    private function sendCommand($command)
    {
        $packet = hex2bin($command);

        socket_sendto(
            $this->socket,
            $packet,
            strlen($packet),
            0,
            $this->ip,
            $this->port
        );

        socket_recvfrom(
            $this->socket,
            $buffer,
            2048,
            0,
            $this->ip,
            $this->port
        );

        return $buffer;
    }

    public function disableDevice()
    {
        // basic command
        $this->sendCommand('5050000000000000');
        return true;
    }

    public function enableDevice()
    {
        $this->sendCommand('5051000000000000');
        return true;
    }

    public function getAttendance()
    {
        // THIS IS SIMPLIFIED DUMP VERSION
        // Many devices return raw attendance via UDP response

        $response = $this->sendCommand('5052000000000000');

        if (!$response) {
            return [];
        }

        // NOTE: real parsing differs per device model
        // we return raw for now (safe fallback)
        return [$response];
    }

    public function disconnect()
    {
        if ($this->socket) {
            socket_close($this->socket);
        }
    }
}
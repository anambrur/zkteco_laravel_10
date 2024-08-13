<?php

namespace App\Services;

use Rats\Zkteco\Lib\ZKTeco;

class ZKTecoService
{
    protected $zk;

    public function __construct()
    {
        $host = env('ZKTECO_HOST', '192.168.0.201');
        $port = env('ZKTECO_PORT', 4370);
        $this->zk = new ZKTeco($host, $port);
    }

    public function connect()
    {
        $this->zk->connect();
    }

    public function getUsers()
    {
        return $this->zk->getUser();
    }

    public function getAttendanceLogs()
    {
        return $this->zk->getAttendance();
    }

    public function disconnect()
    {
        $this->zk->disconnect();
    }
}

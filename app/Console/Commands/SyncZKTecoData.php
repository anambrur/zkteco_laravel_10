<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Rats\Zkteco\Lib\ZKTeco;
class SyncZKTecoData extends Command
{
    // This signature must match what you have scheduled in Kernel.php
    protected $signature = 'sync:zkteco';
    
    protected $description = 'Sync ZKTeco device data to remote server';


    public function handle()
    {
        // Connect to the ZKTeco device
        $zk = new ZKTeco('192.168.0.201', 4370); // Replace with your device IP and port
        $zk->connect();
        $attendance = $zk->getAttendance();
        dd($attendance);

        // Process and send data to remote server
        foreach ($attendance as $record) {
            dd('test');
            // Http::post('https://remote-server.com/api/attendance', $record);
        }

        $zk->disconnect();
        $this->info('Data synced successfully.');
    }
}

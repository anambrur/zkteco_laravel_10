<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\Attendance;
use Rats\Zkteco\Lib\ZKTeco;
use Illuminate\Console\Command;

class SyncZKTecoData extends Command
{
    protected $signature = 'sync:zkteco';
    protected $description = 'Sync ZKTeco device data to remote server';

    public function handle()
    {
        try {
            $zk = new ZKTeco('192.168.0.201', 4370); // Replace with your device IP and port
            $zk->connect();
            $this->info('Attendance and employee data retrieved successfully.');
            $attendanceData = $zk->getAttendance();
            $usersData = $zk->getUser();

            if ($attendanceData && $usersData) {

                foreach ($usersData as $user) {
                    $uid = $user['uid'];
                    $userid = $user['userid'];
                    $name = $user['name'];
                    $role = $user['role'];
                    $password = $user['password'];
                    $cardno = $user['cardno'];

                    Employee::updateOrCreate(
                        [
                            'uid' => $uid,
                            'userid' => $userid,
                            'name' => $name,
                            'role' => $role,
                            'password' => $password,
                            'cardno' => $cardno
                        ]
                    );
                }

                foreach ($attendanceData as $record) {
                    $userId = $record['id'];
                    $timestamp = $record['timestamp'];
                    $state = $record['state'];
                    $type = $record['type'];

                    // Store attendance data in the `attendance` table
                    Attendance::updateOrCreate([
                        'employee_id' => $userId,
                        'state' => $state,
                        'type' => $type,
                        'timestamp' => $timestamp,
                    ]);
                }
            } else {
                $this->error('Failed to retrieve attendance or user data.');
            }

            $zk->disconnect();
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            \Log::error('ZKTeco Error: ' . $e->getMessage());
        }
    }
}

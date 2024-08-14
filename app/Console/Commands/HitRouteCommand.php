<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class HitRouteCommand extends Command
{
    protected $signature = 'route:hit';
    protected $description = 'Hits a specified route every minute';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            // Connect to the ZKTeco device
            $zk = new ZKTeco('192.168.0.201', 4370); // Replace with your device IP and port
            $zk->connect();

            // Retrieve attendance data
            $attendanceData = $zk->getAttendance();

            if ($attendanceData) {
                $this->info('Attendance data retrieved successfully.');

                foreach ($attendanceData as $record) {
                    // Extract user ID and timestamp
                    $userId = $record['user_id']; // Adjust this based on the structure of $record
                    $timestamp = $record['timestamp']; // Adjust this based on the structure of $record
                    $status = $record['status']; // Assuming you have a status (in/out etc.)

                    // Store or update user data in the `users` table
                    $user = User::updateOrCreate(
                        ['id' => $userId], // Assuming user ID is unique
                        ['name' => $record['name'] ?? null] // Add other fields as needed
                    );

                    // Store attendance data in the `attendance` table
                    Attendance::create([
                        'user_id' => $user->id,
                        'timestamp' => $timestamp,
                        'status' => $status,
                    ]);
                }
            } else {
                $this->error('Failed to retrieve attendance data.');
            }

            // Disconnect from the device
            $zk->disconnect();
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            \Log::error('ZKTeco Error: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\ZKTecoService;

class AttendanceController extends Controller
{
    protected $zkService;

    public function __construct(ZKTecoService $zkService)
    {
        $this->zkService = $zkService;
    }

    public function getAttendance()
    {
        $this->zkService->connect();
        $logs = $this->zkService->getAttendanceLogs();

        dd($logs);
        $this->zkService->disconnect();

        return view('attendance.index', compact('logs'));
    }

    public function getUsers()
    {
        $this->zkService->connect();
        $users = $this->zkService->getUsers();
        $this->zkService->disconnect();
        dd($users);

        return view('users.index', compact('users'));
    }

    
}

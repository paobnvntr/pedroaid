<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function logs()
    {
        $logs = Logs::orderBy('created_at', 'ASC')->get();
        return view('logs', compact('logs'));
    }
}

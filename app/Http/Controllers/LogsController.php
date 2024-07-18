<?php

namespace App\Http\Controllers;

use App\Models\Log as ModelLog;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('adminOnly');
    }

    public function index(){   
        $logs = ModelLog::orderBy('created_at', 'desc')->get();
        return view('logs.logs', ['logs' => $logs]);
       
    }
}

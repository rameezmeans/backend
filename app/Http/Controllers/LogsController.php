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
	
	 public function alientechLogs(){   

        $alientechLogs = ModelLog::orderBy('created_at', 'desc')
        ->where('request_type', 'alientech')->get();

        return view('logs.alientech_logs', [
            'alientechLogs' => $alientechLogs,
            
        ]);
       
    }

    public function magicLogs(){   

        $magicLogs = ModelLog::orderBy('created_at', 'desc')
        ->where('request_type', 'magic')->get();

        return view('logs.magic_logs', [
            'magicLogs' => $magicLogs,
            
        ]);
       
    }
    
    public function elorusLogs(){   

        $elorusLogs = ModelLog::orderBy('created_at', 'desc')
        ->where('request_type', 'elorus')->get();
        
        return view('logs.elorus_logs', [
            'elorusLogs' => $elorusLogs,
            
        ]);
       
    }

    public function zohoLogs(){   

        $zohoLogs = ModelLog::orderBy('created_at', 'desc')
        ->where('request_type', 'zoho')->get();
        
        return view('logs.zoho_logs', [
            'zohoLogs' => $zohoLogs,
            
        ]);
       
    }

    public function smsLogs(){   

        $smsLogs = ModelLog::orderBy('created_at', 'desc')
        ->where('request_type', 'sms')->get();
        
        return view('logs.sms_logs', [
            'smsLogs' => $smsLogs,
            
        ]);
       
    }

    public function emailLogs(){   

        $emailLogs = ModelLog::orderBy('created_at', 'desc')
        ->where('request_type', 'email')->get();
        
        return view('logs.email_logs', [
            'emailLogs' => $emailLogs,
            
        ]);
       
    }

    public function index(){   
        $logs = ModelLog::orderBy('created_at', 'desc')->get();
        return view('logs.logs', ['logs' => $logs]);
       
    }
}

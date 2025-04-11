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
	
	 public function autoturnerLogs(){  
         
        $autoTurnerLogs = ModelLog::orderBy('created_at', 'desc')
        ->where('request_type', 'autotuner')->get();

        return view('logs.autoturner_logs', [
            'autoTurnerLogs' => $autoTurnerLogs,
            
        ]);

     }
	 public function alientechLogs(){   

        $alientechLogs = ModelLog::orderBy('created_at', 'desc')
        ->where('request_type', 'alientech')->limit(1000)->get();

        // dd($alientechLogs);

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

    public function stripeLogs(){   

        $stripeLogs = ModelLog::orderBy('created_at', 'desc')
        ->where('request_type', 'stripe')->get();

        return view('logs.stripe_logs', [
            'stripeLogs' => $stripeLogs,
            
        ]);
       
    }

    public function paypalLogs(){   

        $paypalLogs = ModelLog::orderBy('created_at', 'desc')
        ->where('request_type', 'paypal')->get();

        return view('logs.paypal_logs', [
            'paypalLogs' => $paypalLogs,
            
        ]);
       
    }

    public function paypalDetails($id){  

        $record = ModelLog::findOrFail($id);
        $logsUrl = 'paypal-logs';

        return view('logs.details', [
            'record' => $record,
            'logsUrl' => $logsUrl,
            
        ]);
    }

    public function stripeDetails($id){  
        
        $record = ModelLog::findOrFail($id);
        $logsUrl = 'stripe-logs';

        return view('logs.details', [
            'record' => $record,
            'logsUrl' => $logsUrl,
            
        ]);
    }
    
    public function elorusDetails($id){   
        $record = ModelLog::findOrFail($id);
        $logsUrl = 'elorus-logs';

        return view('logs.details', [
            'record' => $record,
            'logsUrl' => $logsUrl,
            
        ]);
    }

    public function zohoDetails($id){   
        $record = ModelLog::findOrFail($id);
        $logsUrl = 'zoho-logs';

        return view('logs.details', [
            'record' => $record,
            'logsUrl' => $logsUrl,
            
        ]);
    }

    public function magicDetails($id){   
        $record = ModelLog::findOrFail($id);
        $logsUrl = 'magic-logs';

        return view('logs.details', [
            'record' => $record,
            'logsUrl' => $logsUrl,
            
        ]);
    }

    public function alientechDetails($id){   
        $record = ModelLog::findOrFail($id);
        $logsUrl = 'alientech-logs';

        return view('logs.details', [
            'record' => $record,
            'logsUrl' => $logsUrl,
            
        ]);
    }

    public function elorusLogs(){   

        $elorusLogs = ModelLog::orderBy('created_at', 'desc')
        ->where('request_type', 'elorus')->limit(2000)->get();
        
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

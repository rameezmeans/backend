<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\FrontEnd;
use App\Models\PaymentLog;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaymentLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('adminOnly');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function customers()
    {
        $customers = User::where('front_end_id', 1)
        ->whereNot('name', 'Live Chat')
        ->whereNot('name', 'Live Chat Sub')
        ->whereNULL('subdealer_group_id')
        ->where('role_id', 4)->get();

        return view('payment_logs.customers', ['customers' => $customers]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function payments($id)
    {
        $customer = User::findOrFail($id);
        $allPayments = $customer->all_payments;
        
        return view('payment_logs.payments', [
            'allPayments' => $allPayments,
            'customer' => $customer
        ]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function paymentLogs($id)
    {
        $customer = User::findOrFail($id);

        $paymentLogs = $customer->payment_logs;
        
        return view('payment_logs.logs', [
            'paymentLogs' => $paymentLogs,
            'customer' => $customer
        ]);
    }

    public function paymentDetails($id){

        $credit = Credit::findOrFail($id);
        $user = User::findOrFail($credit->user_id);
        $group = $user->group;

        return view('payment_logs.details', [
            'credit' => $credit,
            'user' => $user,
            'group' => $group,
        ]);
    }

    public function livePayments(){

        // $allPayments = Credit::orderBy('created_at', 'desc')->where('price_payed', '>', 0)->orWhere('gifted', 1)->where('credits', '>', 0)->get();
        
        return view('payment_logs.all_payments_live', [
            
            
        ]);   

    }

    public function paymentsTable(Request $request){
            
            $data = Credit::select('*')->where('credits', '>', 0)->where('price_payed', '>', 0);

            if ($request->filled('from_date') && $request->filled('to_date')) {

                $data = $data->whereBetween('created_at', [$request->from_date, $request->to_date]);

            }
            
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('frontend', function($row){

                $frontEndID = $row->front_end_id;

                if($frontEndID == 1){
                    $btn = '<span class="label bg-primary text-white">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }
                else if($frontEndID == 2){
                    $btn = '<span class="label bg-warning">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }
                else if($frontEndID == 3){
                    $btn = '<span class="label bg-info text-white">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }

                return $btn;

        })

        
        

        ->editColumn('created_at', function ($user) {
            return [
                'display' => e($user->created_at->format('d-m-Y')),
                'timestamp' => $user->created_at->timestamp
            ];
        })
        ->filterColumn('created_at', function ($query, $keyword) {
            $query->whereRaw("DATE_FORMAT(created_at,'%d-%m-%Y') LIKE ?", ["%$keyword%"]);
        })

        ->addColumn('details', function($row){
            return '<a class="btn btn-warning text-black" target="_blank" href="'.route("payment-details", $row->id).'">Payment Details</a>';
        })
        ->addColumn('elorus', function($row){
            if($row->elorus_permalink){
                return '<a class="btn btn-warning text-black" target="_blank" href="'.$row->elorus_permalink.'">Go To Elorus</a>';
            }
        })
        ->addColumn('zohobooks', function($row){
            if($row->zohobooks_id){
                return '<a class="btn btn-warning text-black" target="_blank" href="'.'https://books.zoho.com/app/8745725#/invoices/'.$row->zohobooks_id.'">Go To Zohobooks</a>';
            }
        })

        ->rawColumns(['frontend','type','details','elorus','zohobooks'])
        ->make(true);
    }

    public function allPayments(){

        // $allPayments = Credit::orderBy('created_at', 'desc')->where('price_payed', '>', 0)->orWhere('gifted', 1)->where('credits', '>', 0)->get();
        
        return view('payment_logs.all_payments_live', [
            
            // 'allPayments' => $allPayments,
        ]);   

    }

    public function allPaymentsAdmin(){
        $allPayments = Credit::orderBy('created_at', 'desc')->where('gifted', 1)->where('credits', '>', 0)->get();
        
        return view('payment_logs.all_payments', [
            'allPayments' => $allPayments,
            
        ]);
    }

    public function allPaymentLogs()
    {
        $allPaymentLogs = PaymentLog::orderBy('created_at', 'desc')->get();
        
        return view('payment_logs.all_logs', [
            'allPaymentLogs' => $allPaymentLogs,
            
        ]);
    }
}


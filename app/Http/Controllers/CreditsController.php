<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Group;
use App\Models\Price;
use App\Models\User;
use Illuminate\Http\Request;
use PDF;

class CreditsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('adminOnly');
    }

    public function credits() {
        $customers = User::where('is_customer', 1)->get();
        return view('credits.credits', ['customers' =>$customers]);
    }

    public function creditsReports() {
        $groups = Group::all();
        return view('credits.report', ['groups' => $groups]);
    }

    public function getCreditsReport(Request $request) {

        $html = '';
        
        if($request->group == 'all_groups'){
            $users = User::where('is_customer', 1)->get();
        }
        else if($request->group == 'no_group'){
            $users = User::where('is_customer', 1)->whereNull('group_id')->get();
        }
        else{
            $users = User::where('is_customer', 1)->where('group_id', $request->group)->get();
        }

        $data = [];

        if(sizeof($users) == 0){
            $html .= '<h5 class="m-t-40"><b>No users in this group.</b></h5>';
        }

        else{

            foreach($users as $user){

                $creditsForUsers = [];
                $creditsForUsers ['user']= $user;
                $creditObj = Credit::orderBy('created_at', 'desc')->where('user_id', $user->id);

                if($request->start){
                    $date = str_replace('/', '-', $request->start);
                    $startDate = date('Y-m-d', strtotime($date));

                    $creditObj = $creditObj->whereDate('created_at', '>=' , $startDate);
                }

                if($request->end){
                    $date = str_replace('/', '-', $request->end);
                    $endDate = date('Y-m-d', strtotime($date));

                    $creditObj = $creditObj->whereDate('created_at', '<=' , $endDate);
                }

                $creditsForUsers ['credits'] = $creditObj->get();

                $data []= $creditsForUsers;
            }

            foreach($data as $row){
                $html .= '<h5 class="m-t-40"><b>Customer:</b> '.$row['user']->name.'</h5>';

                if(sizeof($row['credits']) != 0){
                    $html .= '<table class="table table-hover demo-table-search table-responsive-block no-footer" >';
                    $html .= '<tr><th>Date</th><th>Credits</th><th>Total Credits</th><th>Reason to consume or earn</th><th>Invoice Ref.</th><th>Amount</th></tr>';
                    $html .= '<tr>';

                    $total = $row['user']->total_credits();

                        foreach( $row['credits'] as $record ){
                            if($record->credits < 0){
                                if($record->file){
                                    $html .= '<tr><td>'.$record->created_at->format('d/m/Y').'</td><td><label class="label label-danger">'.$record->credits.'</label></td><td><label class="label label-info">'.$total.'</label></td><td><img style="width: 10%;" src="'.get_image_from_brand($record->file->brand).'" >  '.$record->file->vehicle()->Name .' '. $record->file->engine .' '. $record->file->vehicle()->TORQUE_standard .'</td><td>'.$record->invoice_id.'</td><td></td></tr>';
                                }
                                else{
                                    $html .= '<tr><td>'.$record->created_at->format('d/m/Y').'</td><td><label class="label label-danger">'.$record->credits.'</label></td><td><label class="label label-info">'.$total.'</label></td><td>'.$record->message_to_credit. '</td><td>'.$record->invoice_id.'</td><td></td></tr>';
                                }
                            }
                            else{
                                if($record->price_payed > 0){
                                    $html .= '<tr><td>'.$record->created_at->format('d/m/Y').'</td><td><label class="label label-success">'.$record->credits.'</label></td><td><label class="label label-info">'.$total.'</label></td><td>'.$record->message_to_credit. '</td><td>'.$record->invoice_id.'</td><td>€'.$record->price_payed.'</td></tr>';
                                }
                                else{
                                    $html .= '<tr><td>'.$record->created_at->format('d/m/Y').'</td><td><label class="label label-success">'.$record->credits.'</label></td><td><label class="label label-info">'.$total.'</label></td><td>'.$record->message_to_credit. '</td><td>'.$record->invoice_id.'</td><td></td></tr>';
                                }
                            }

                            $total -= $record->credits;
                        }
                    $html .= '</tr>';

                    $html .= '</table>';
                }
                else{
                    $html .= '<p>No Credits Record.</p>';
                }            
            }
        }

        return response()->json(['html' =>$html], 200);
    }

    public function updateCredits(Request $request) {

        $customer = User::findOrFail($request->user_id);

        $difference = (float) $request->total_credits_updated - (float) $customer->sum();

        if($difference != 0){

        $credit = new Credit();
        $credit->credits = $difference;
        $credit->user_id = $customer->id;
        $credit->stripe_id = NULL;
        $credit->price_payed = 0;
        $credit->message_to_credit = $request->message_to_credit;
        $credit->invoice_id = 'Admin-'.mt_rand(1000,9999);
        $credit->save();

        return redirect()->route('edit-credit', $customer->id)->with(['success' => 'Credit udpated, successfully.']);

       }
       
       return redirect()->route('edit-credit', $customer->id)->with(['success' => 'Credit is not changed.']);
    }

    public function EditCredit($id) {
        $customer = User::findOrFail($id);
        $credits = $customer->credits;
        return view('credits.edit', ['customer' => $customer, 'credits' => $credits]);
    }

    public function unitPrice(){
        $creditPrice = Price::where('label', 'credit_price')->first();
        return view('credits.unit_price', ['creditPrice' => $creditPrice]);
    }

    public function updatePrice(Request $request){
        $creditPrice = Price::where('label', 'credit_price')->first(); 

        if($creditPrice){
            $creditPrice->label = "credit_price";
            $creditPrice->value = $request->credit_price;
            $creditPrice->save();
        }
        else {
            $newPrice = new Price();
            $newPrice->label = "credit_price";
            $newPrice->value = $request->credit_price;
            $newPrice->save();
        }

        return redirect()->route('unit-price')->with(['success' => 'Price updated, successfully.']);

    }

    /**
     * Print the pdf
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function makePDF(Request $request)
    {
        $invoice = Credit::findOrFail($request->id);
        $pdf = PDF::loadView('credits.invoice', ['invoice' => $invoice]);
        return $pdf->download($invoice->invoice_id.'.pdf');
    }
}

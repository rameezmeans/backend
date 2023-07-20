<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\FrontEnd;
use App\Models\Group;
use App\Models\Price;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Http\Request;
use PDF;

use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;

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

        $customerRole = Role::where('name', 'customer')->first();
        $subdealerRole = Role::where('name', 'subdealer')->first();

        $customers = User::where('role_id', $customerRole->id)
        ->where('name' ,'!=', 'Live Chat')
        ->where('name' ,'!=', 'Live Chat Sub')
        ->orWhere('role_id', $subdealerRole->id)
        ->get();

        return view('credits.credits', ['customers' =>$customers]);
    }

    public function creditsReports() {
        $groups = Group::all();
        return view('credits.report', ['groups' => $groups]);
    }

    public function getCreditsReport(Request $request) {

        $html = '';
        
        if($request->group == 'all_groups'){
            $users = get_customers();
        }
        else if($request->group == 'no_group'){
             
            $userRoles = get_customers();
            
            $users = [];
            foreach($userRoles as $user){
                
                if($user->group_id == NULL){
                    $users []=  $user;
                }
            }
            
        }
        else{
            
            $userRoles = get_customers();
            
            $users = [];
            foreach($userRoles as $user){
                
                if ($user->group_id == $request->group ){
                    $users []=  $user;
                }
            }

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
                if($row['user']->front_end_id == 1){
                    $bgClasses = 'bg-primary text-white';
                }
                else{
                    $bgClasses = 'bg-warning text-black';
                }
                $span = ' <span class="label '.$bgClasses.'">'.FrontEnd::findOrFail($row['user']->front_end_id)->name.'</span>';
                $html .= '<h5 class="m-t-40"><b>Customer:</b> '.$row['user']->name.$span.'</h5>';
                

                if(sizeof($row['credits']) != 0){
                    $html .= '<table class="table table-hover demo-table-search table-responsive-block no-footer" >';
                    $html .= '<tr><th>Date</th><th>Credits</th><th>Total Credits</th><th>Reason to consume or earn</th><th>Invoice Ref.</th><th>Amount</th></tr>';
                    $html .= '<tr>';

                    $total = $row['user']->total_credits();
                    $totalMoney = 0;
                    $creditsBought = 0;
                    $creditsSpent = 0;

                        foreach( $row['credits'] as $record ){
                            if($record->credits < 0){
                                if($record->file){
                                    $html .= '<tr><td>'.$record->created_at->format('d/m/Y').'</td><td><label class="label label-danger">'.$record->credits.'</label></td><td><label class="label label-danger">'.$total.'</label></td><td><img style="width: 10%;" src="'.get_image_from_brand($record->file->brand).'" >  '.$record->file->vehicle()->Name .' '. $record->file->engine .' '. $record->file->vehicle()->TORQUE_standard .'</td><td>'.$record->invoice_id.'</td><td></td></tr>';
                                    $creditsSpent += $record->credits;
                                }
                                else{
                                    $html .= '<tr><td>'.$record->created_at->format('d/m/Y').'</td><td><label class="label label-danger">'.$record->credits.'</label></td><td><label class="label label-danger">'.$total.'</label></td><td>'.$record->message_to_credit. '</td><td>'.$record->invoice_id.'</td><td></td></tr>';
                                }
                               
                            }
                            
                            else{
                                if($record->price_payed > 0){

                                    $html .= '<tr><td>'.$record->created_at->format('d/m/Y').'</td><td><label class="label label-success">'.$record->credits.'</label></td><td><label class="label label-warning">'.$total.'</label></td><td>'.$record->message_to_credit. '</td><td>'.$record->invoice_id.'</td><td>€'.$record->price_payed.'</td></tr>';
                                    
                                    $creditsBought += $record->credits;
                                }
                                else{

                                    if(!$record->gifted){
                                        $creditsBought += $record->credits;
                                        $html .= '<tr><td>'.$record->created_at->format('d/m/Y').'</td><td><label class="label label-success">'.$record->credits.'</label></td><td><label class="label label-warning">'.$total.'</label></td><td>'.$record->message_to_credit. '</td><td>'.$record->invoice_id.'</td><td></td></tr>';

                                    }
                                    else{

                                        $html .= '<tr><td>'.$record->created_at->format('d/m/Y').'</td><td><label class="label label-info">'.$record->credits.'</label></td><td><label class="label label-info">'.$total.'</label></td><td>'.$record->message_to_credit. '</td><td>'.$record->invoice_id.'</td><td></td></tr>';
                                    }
                                }
                            }

                            $total -= $record->credits;
                            $totalMoney += $record->price_payed;
                        }

                    $html .= '<tr><td><b>Total</b></td><td><label class="label label-danger">Credit Consumed:'.-1*$creditsSpent.'</label></td><td><label class="label label-warning">Credit Bought:'.$creditsBought.'</label></td><td></td><td></td><td>Money Spent: €'.$totalMoney.'</td></tr>';

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

        if($difference != 0) {

        $credit = new Credit();
        $credit->credits = $difference;
        $credit->user_id = $customer->id;
        $credit->stripe_id = NULL;
        
        if( isset($request->gifted) && $request->gifted == 'on' ){
            $credit->gifted = 1;
            $credit->price_payed = 0;
        }
        else{
            $credit->gifted = 0;

            $request->validate([
                'price_payed' => 'required|numeric|min:1'
            ]);

            $credit->price_payed = $request->price_payed;
        }

        $credit->message_to_credit = $request->message_to_credit;
        $credit->invoice_id = 'Admin-'.mt_rand(1000,9999);
        $credit->save();

        return redirect()->route('edit-credit', $customer->id)->with(['success' => 'Credit udpated, successfully.']);

       }
       
       return redirect()->route('edit-credit', $customer->id)->with(['info' => 'Credits are not changed.']);
    }

    public function setCreditInformation(Request $request) {

        $credit = Credit::findOrFail($request->id);
        $credit->price_payed = $request->price_payed;
        $credit->credits =$request->credits;
        $credit->save();
        
        return redirect()->route('edit-credit', $credit->user_id)->with(['success' => 'Credit udpated, successfully.']);

    }

    public function UpdateIndividualCredit($id) {
        $credit = Credit::findOrFail($id);
        return view( 'credits.update-individual', ['credit' => $credit] );
    }
    public function EditCredit($id) {
        $customer = User::findOrFail($id);
        $credits = $customer->credits;
        return view('credits.edit', ['customer' => $customer, 'credits' => $credits]);
    }

    public function unitPrice(){
        $creditPrice = Price::where('label', 'credit_price')->whereNull('subdealer_group_id')->first();
        return view('credits.unit_price', ['creditPrice' => $creditPrice]);
    }

    public function updatePrice(Request $request){
        $creditPrice = Price::where('label', 'credit_price')->whereNull('subdealer_group_id')->first();


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

        $user = User::findOrFail($invoice->user_id);

        if($invoice->type == 'stripe'){
            $account = $user->stripe_payment_account();
        }
        else{
            $account = $user->paypal_payment_account();
        }

        $price = Price::where('label', 'credit_price')->whereNull('subdealer_group_id')->first();

        $discount = null;
        
        if($invoice->type == 'package'){
            $discount = ($invoice->credits * $price->value) - $invoice->price_payed;
        }
        
        $client = new Party([
            'name'          => $account->senders_name,
            'phone'         => $account->senders_phone_number,
            'vat'         => $account->company_id,
            'code'         => $account->zip,
            'city'         => $account->city,
            'country'         => $account->country,
            'custom_fields' => [
                'company'         => $account->company,
                'address'        => $account->senders_address,
            ],
        ]);

        $customer = new Party([
            'name'          => $user->name,
            'vat'       => $user->company_id,
            'code'       => $user->zip,
            'city'       => $user->city,
            'country'       => $user->country,
            'phone'         => $user->phone,
            'custom_fields' => [
                'company'         => $user->company_name,
                'address'        => $user->address,
            ],
        ]);
        
        if($discount){

            $items = [
                (new InvoiceItem())
                        ->title('Tuning Credits Package')
                    ->description('You can use these credits to buy the services.')
                    ->pricePerUnit($invoice->price_without_tax)
                    ->quantity(1)
                    ->tax($invoice->tax),
                   
            ];
        }
        else{

            $items = [
                (new InvoiceItem())
                    ->title('Tuning Credits')
                    ->description('You can use these credits to buy the services.')
                    ->pricePerUnit($price->value)
                    ->quantity($invoice->credits)
                    ->tax($invoice->tax),
            ];

        }

        $notes = [
            $account->note,
        ];
        $notes = implode("<br>", $notes);

        $invoice = Invoice::make('invoice')
            ->series($invoice->invoice_id)
            ->status(__('invoices::invoice.paid'))
            ->serialNumberFormat('{SERIES}')
            ->seller($client)
            ->buyer($customer)
            ->dateFormat('d/m/Y')
            // ->payUntilDays(14)
            ->currencySymbol('€')
            ->currencyCode('EUR')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->filename($invoice->invoice_id)
            ->addItems($items)
            ->notes($notes)
            ->logo(public_path('/company_logos/'.$account->companys_logo))
            // You can additionally save generated invoice to configured disk
            ->save('public');

        // $link = $invoice->url();
        // Then send email to party with link

        // And return invoice itself to browser or have a different view

        return $invoice->download();
    }
}

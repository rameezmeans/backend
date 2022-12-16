<?php

namespace App\Http\Controllers;

use App\Models\Credit;
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
    }

    public function credits() {
        $customers = User::where('is_customer', 1)->get();
        return view('credits.credits', ['customers' =>$customers]);
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

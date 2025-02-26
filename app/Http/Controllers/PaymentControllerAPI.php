<?php

namespace App\Http\Controllers;

use App\Http\Api\Controllers\PaymentAPIController;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentControllerAPI extends Controller
{
    public function addCreditsAPI(Request $request) {

        $newPaymentObj = new PaymentAPIController();
        $newZohoObj = new ZohoMainController();

        $user = User::findOrFail($request->user_id);
        $sessionID = $request->session_id;
        $credits = $request->credits;
        $type = $request->type;

        $invoice = $newPaymentObj->addCredits($user, $sessionID, $credits, $type);
        $newZohoObj->createZohobooksInvoice($user, $invoice, false, $type, 0);

        return response()->json([
            'message' => 'New payment happened successfully.',
            'invoice' => $invoice,
        ], 201);

    }
}

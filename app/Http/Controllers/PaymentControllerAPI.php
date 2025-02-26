<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentControllerAPI extends Controller
{
    public function addCreditsAPI(Request $request) {
        dd($request->all());
    }
}

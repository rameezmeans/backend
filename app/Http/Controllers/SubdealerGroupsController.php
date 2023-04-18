<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubdealerGroupsController extends Controller
{
    private $token;

    public function __construct(){

        $this->middleware('auth');
    }

    

}

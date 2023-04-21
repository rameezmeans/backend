<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubdealerGroupsController extends Controller
{
    private $token;

    public function __construct(){

        $this->middleware('auth');
    }

    public function index(){
        return view('subdealer_groups.index');
    }

    public function create(){
        return view('subdealer_groups.create');
    }

}

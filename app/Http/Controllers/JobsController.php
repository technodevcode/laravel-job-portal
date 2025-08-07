<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobsController extends Controller
{
    //this method show job detial page
    public function index(){
        return view('front.jobs');
    }
}

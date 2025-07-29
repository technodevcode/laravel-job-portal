<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function registration(){
        return view('front.account.registration');
    }

    // public function processRegistration(Request $request){
    //     $validator = 
    // }

    public function login(){
        
    }
}

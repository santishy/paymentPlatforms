<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Request $request){
      $rules = [
        'value' => ['required','min:5','numeric'],
        'currency' => ['required','exists:currencies,iso'],
        'payment_platform' => ['required','exists:payment_platforms,id']
      ];
      $request->validate($rules);
      return $request->all();
    }
    public function approval(){

    }
    public function cancelled(){

    }
}
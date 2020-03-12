<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resolvers\PaymentPlatformResolve;
use App\Services\PaypalService;

class PaymentController extends Controller
{
  protected $paymentPlatformResolve;

  public function __construct(PaymentPlatformResolve $paymentPlatformResolve){
    $this->middleware('auth');
    $this->paymentPlatformResolve = $paymentPlatformResolve;
  }
  public function pay(Request $request){

      $rules = [
        'value' => ['required','min:5','numeric'],
        'currency' => ['required','exists:currencies,iso'],
        'payment_platform' => ['required','exists:payment_platforms,id']
      ];

      $request->validate($rules);

      $paymentPlatform = $this->paymentPlatformResolve->serviceResolve($request->payment_platform);

      return $paymentPlatform->handlePayment($request);
    }

    public function approval(){

        $paymentPlatform = resolve(PaypalService::class);

        return $paymentPlatform->handleApprove();
    }
    public function cancelled(){
      return redirect()
            ->route('home')
            ->withErrors(['payment' => 'You cancelled the payment.']);
    }
}

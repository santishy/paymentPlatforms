<?php
namespace App\Services;
use App\traits\ConsumesExternalServices;
use Illuminate\Http\Request;

class MercadoPagoService{

  protected $baseUri;
  protected $key;
  protected $secret;

  use ConsumesExternalServices;

  public function __construct(){
    $this->baseUri = config('services.mercadopago.base_uri');
    $this->key = config('services.mercadopago.key');
    $this->secret = config('services.mercadopago.secret');
  }

  public function resolveAuthorization(&$queryParams, &$formParams, &$headers){
    $queryParams['access_token'] = $this->resolveAccessToken();
  }

  public function decodeResponse($response){
    return json_decode($response);
  }

  public function resolveAccessToken(){
    return $this->secret;
  }


  public function resolveFactor($currency){
    $zeroDecimalCurrency = ['JPY'];
    if(in_array(strtoupper($currency),$zeroDecimalCurrency)){
      return 1;
    }
    return 100;
  }
  public function handlePayment(Request $request){
    $request->validate([
      'paymentMethodId' => 'required',
      'cardToken' => 'required',
      'email' => 'required',
    ]);
    $payment = $this->createPayment($request->value,$request->currency,$request->paymentMethodId,$request->cardToken,$request->email);
    if($payment->status == 'approved'){
      $name = $payment->payer->name;
      $currency = strtoupper($payment->currency_id);
      $amount = number_format($payment->transaction_amount,2);
      $originalCurrency = $request->currency;
      $originalAmount = $request->value;
      return redirect(route('home'))
              ->withSuccess(["payment" => "Thanks {$name} we received your {$originalAmount}{$originalCurrency} payment ({$amount}{$currency)."]);
    }
  return redirect('home')->withErrors('We were unable to confirm your payment. Try again, please');
  }
  public function createPayment($value,$currency,$paymentMethodId,$cardToken,$email,$installments = 1){
    return $this->makeRequest('POST',
        '/v1/payments',
        [],
        [
          'payer' => [
            'email' => $email
          ],
          'binary_mode' => true,
          'transaction_amount' => round($value * $this->resolveFactor($currency)),
          'payment_method_id' => $paymentMethodId,
          'token' => $cardToken,
          'installments' => $installments,
          'statement_descriptor' => config('app.name')
        ],
        [],
        $isJson = true);
  }
}

?>

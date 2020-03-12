<?php
namespace App\Services;
use App\traits\ConsumesExternalServices;
use Illuminate\Http\Request;

class PaypalService{

  protected $baseUrl;
  protected $client_id;
  protected $client_secret;

  use ConsumesExternalServices;

  public function __construct(){
    $this->baseUrl = config('services.paypal.base_url');
    $this->client_id = config('services.paypal.client_id');
    $this->client_secret = config('services.paypal.client_secret');
  }

  public function resolveAuthorization(&$queryParams, &$formParams, &$headers){
    $headers['Authorization'] = $this->resolveAccessToken();
  }

  public function decodeResponse($response){
    return json_decode($response);
  }

  public function resolveAccessToken(){
    $credentials = base64_encode("{$this->client_id}:{$this->client_secret}");
    return "Basic {$credentials}";
  }

  public function createOrder($value,$currency){
    $response = $this->makeRequest('POST','/v2/checkout/orders',
                                    [],
                                    [
                                      "intent" => 'CAPTURE',
                                      "application_context" => [
                                        "brand_name" => config('app.name'),
                                        "shipping_preference" => 'NO_SHIPPING',
                                        "user_action" => 'PAY_NOW',
                                        "return_url" => route('approval'),
                                        "cancel_url" => route('cancelled'),
                                      ],
                                      "purchase_units" => [
                                        0 => [
                                          "amount" => [
                                            "currency_code" => strtoupper($currency),
                                            "value" => round($value * $factor = $this->resolveFactor($currency)) / $factor
                                          ]
                                        ]
                                      ]
                                    ],
                                    [],
                                    $isJsonRequest = true);
    return $response;
  }
  public function capturePayment($order_id){
    $response = $this->makeRequest('POST',
                                   "/v2/checkout/orders/{$order_id}/capture",
                                   [],
                                   [],
                                   ['Content-Type' => 'application/json']);
    return $response;
  }
  public function resolveFactor($currency){
    $zeroDecimalCurrency = ['JPY'];
    if(in_array(strtoupper($currency),$zeroDecimalCurrency)){
      return 1;
    }
    return 100;
  }
  public function handlePayment(Request $request){
    $response = $this->createOrder($request->value,$request->currency);
    $links = collect($response->links);
    session()->put('approveId',$response->id);
    return redirect($links->where('rel','approve')->first()->href);
  }
  public function handleApprove(){
    if(session()->has('approveId')){

        $payment = $this->capturePayment(session()->get('approveId'));
        $name = $payment->payer->name->given_name;
        $amount = $payment->purchase_units[0]->payments->captures[0]->amount->value;
        $currency = $payment->purchase_units[0]->payments->captures[0]->amount->currency_code;
        return redirect()
               ->route('home')
               ->withSuccess(["payment" => "Thanks {$name} we received your {$amount}{$currency} payment."]);
    }
    return redirect()
           ->route('home')
           ->withErrors('we cannot capture your payment.Try again, please');

  }
}

?>

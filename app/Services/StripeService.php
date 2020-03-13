<?php
namespace App\Services;
use App\traits\ConsumesExternalServices;
use Illuminate\Http\Request;

class StripeService{

  protected $baseUri;
  protected $key;
  protected $secret;

  use ConsumesExternalServices;

  public function __construct(){

    $this->baseUri = config('services.stripe.base_uri');
    $this->key = config('services.stripe.key');
    $this->secret = config('services.stripe.secret');

  }

  public function resolveAuthorization(&$queryParams, &$formParams, &$headers){

    $headers['Authorization'] = $this->resolveAccessToken();

  }

  public function decodeResponse($response){

    return json_decode($response);

  }

  public function resolveAccessToken(){

    return "Bearer {$this->secret}";
    
  }


  public function resolveFactor($currency){
    $zeroDecimalCurrency = ['JPY'];
    if(in_array(strtoupper($currency),$zeroDecimalCurrency)){
      return 1;
    }
    return 100;
  }
  public function handlePayment(Request $request){

  }
  public function handleApprove(){


  }
}

?>

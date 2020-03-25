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
    dd($request->all());
  }

}

?>
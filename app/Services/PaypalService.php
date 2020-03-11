<?php
namespace App\Services;
use App\traits\ConsumesExternalServices;

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
}
 ?>

<?php
namespace App\Services;
use App\traits\ConsumesExternalServices;
use Illuminate\Http\Request;

class CurrencyConversionService{

  protected $baseUri;
  protected $apiKey;

  use ConsumesExternalServices;

  public function __construct(){
    $this->baseUri = config('services.currency_conversion.base_uri');
    $this->apiKey = config('services.currency_conversion.api_key');
  }

  public function resolveAuthorization(&$queryParams, &$formParams, &$headers){
    $queryParams['apiKey'] = $this->resolveAccessToken();
  }

  public function decodeResponse($response){
    return json_decode($response);
  }

  public function resolveAccessToken(){
    return $this->apiKey;
  }

  public function convertCurrency($from,$to){

  }
}

?>

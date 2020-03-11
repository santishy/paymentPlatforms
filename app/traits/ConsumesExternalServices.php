<?php
namespace App\traits;
use GuzzleHttp\Client;

trait ConsumesExternalServices
{
  public function makeRequest( $method,$requestUrl,$queryParams = [],$formParams = [], $headers = [], $isJsonRequest = false)
  {
    $client = new Client([
      'base_uri' => $this->baseUrl,
    ]);
    if(method_exists($this,'resolveAuthorization')){
      $this->resolveAuthorization($queryParams,$formParams,$headers);
    }

    $response = $client->request([$method,
                                  $requestUrl,
                                  $isJsonRequest ? 'json' : 'form_params' => $formParams,
                                  'headers' => $headers,
                                  'query' => $queryParams]);
    dd($response);
    $response = $response->getBody()->getContents();
    if(method_exists($this,'decodeResponse')){
      $response = $this->decodeResponse($response);
    }
    return $response;
  }
}

?>

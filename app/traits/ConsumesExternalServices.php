<?php
namespace App\traits;
trait ConsumesExternalServices
{
  function makeRequest( $method,
                        $requestUrl,
                        $queryParams = [],
                        $formParams = [],
                        $headers = [],
                        $isJsonRequest = false)
  {
  }
}

?>

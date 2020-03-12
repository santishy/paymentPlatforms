<?php

namespace App\Resolvers;

use App\PaymentPlatform;

class PaymentPlatformResolve{

  protected $paymentPlatforms;

  public function __construct(){
    $this->paymentPlatforms = PaymentPlatform::all();
  }

  public function serviceResolve($paymentPlatform_id){
      $name = strtolower($this->paymentPlatforms->where('id',$paymentPlatform_id)->first()->name);
      $service = config("services.{$name}.class");
      if($service){
        return resolve($service);
      }
      throw new \Exception('This selected payment platform is not in the configuration');
  }

}

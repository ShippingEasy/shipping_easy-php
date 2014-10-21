<?php

class ShippingEasy_Object
{
  public function request($meth, $path, $params=null, $payload = null, $apiKey = null, $apiSecret = null)
  {
    $requestor = new ShippingEasy_ApiRequestor();
    return $requestor->request($meth, $path, $params, $payload, $apiKey, $apiSecret);
  }

}


<?php

class ShippingEasy_Object
{  
  public function request($meth, $path, $params=null, $payload = null)
  {
    $requestor = new ShippingEasy_ApiRequestor();
    $requestor->request($meth, $path, $params, $payload);  
  }

}


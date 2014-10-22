<?php

class ShippingEasy_SignedUrl
{
  public function __construct($http_method=null, $path=null, $params=null, $json_body=null, $api_timestamp=null, $api_key = null, $api_secret = null)
  {
    $api_secret = isset($api_secret) ? $api_secret : ShippingEasy::$apiSecret;    
    $params["api_key"] = isset($api_key) ? $api_key : ShippingEasy::$apiKey;
    $params["api_timestamp"] = isset($api_timestamp) ? $api_timestamp : time();
    $signature_object = new ShippingEasy_Signature($api_secret, $http_method, $path, $params, $json_body);
    $params["api_signature"] = $signature_object->encrypted();        
    
    $this->params = $params;
    $this->path = $path;
  }

  public function getParams()
  {
    return $this->params;
  }
  
  public function getPath()
  {
    return $this->path;
  }
  
  public function toString()
  {
    $url = ShippingEasy::$apiBase;
    $url .= $this->getPath();
    $url .= "?" . http_build_query($this->getParams());
    return $url;
  }
    
}

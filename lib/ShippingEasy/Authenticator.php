<?php

class ShippingEasy_Authenticator
{
  
  # Instantiates a new authenticator object. 
  # 
  # http_method - The method of the http request. E.g. "post" or "get".
  # path - The path of the request's uri. E.g. "/orders/callback"
  # params - An associative array of the request's query string parameters. E.g. array("api_signature" => "asdsadsad", "api_timestamp" => "1234567899")
  # json_body - The request body as a JSON string.
  # api_secret - The ShippingEasy API secret for the store. Defaults to the global configuration if set.
  #
  public function __construct($http_method=null, $path=null, $params=null, $json_body=null, $api_secret=null)
  {
    $api_secret = isset($api_secret) ? $api_secret : ShippingEasy::$apiSecret;
    $this->supplied_signature_string = $params["api_signature"];
    unset($params["api_signature"]);
    $this->expected_signature = new ShippingEasy_Signature($api_secret, $http_method, $path, $params, $json_body);
  }

  public function getExpectedSignature()
  {
    return $this->expected_signature;
  }

  public function getSuppliedSignatureString()
  {
    return $this->supplied_signature_string;
  }
  
  public function isAuthenticated()
  {
    return $this->getExpectedSignature()->equals($this->getSuppliedSignatureString());
  }
   
}
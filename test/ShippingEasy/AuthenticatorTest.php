<?php

class ShippingEasy_AuthenticatorTest extends UnitTestCase
{
  public function testIsAuthenticated()
  {
    $secret = "f5cd6a754f3ed64ea8697be6f662910fe7d7e9b0bee47a23214964a6a12db69f";
    $method = "post";
    $path = "/api/orders";
    $params = array("foo" => "bar", "xyz" => "123", "api_timestamp" => "1390928206");
    $json_body = json_encode(array("orders" => array("id" => "1234")));
    $signature = new ShippingEasy_Signature($secret, $method, $path, $params, $json_body);    
    $params["api_signature"] = $signature->encrypted();
    
    $authenticator = new ShippingEasy_Authenticator($method, $path, $params, $json_body, $secret);
    $this->assertTrue($authenticator->isAuthenticated());
    
    $authenticator = new ShippingEasy_Authenticator($method, $path, $params, null, $secret);
    $this->assertFalse($authenticator->isAuthenticated());
  }
  
}

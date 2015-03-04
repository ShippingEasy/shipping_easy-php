<?php

class ShippingEasy_SignedUrlTest extends UnitTestCase
{
  public function testToString()
  {
    ShippingEasy::setApiBase('https://app.shippingeasy.com');
    ShippingEasy::setApiKey('XYZ123');
    ShippingEasy::setApiSecret('f5cd6a754f3ed64ea8697be6f662910fe7d7e9b0bee47a23214964a6a12db69f');
    
    $method = "post";
    $path = "/api/orders";
    $params = array("foo" => "bar", "xyz" => "123");
    $json_body = array("orders" => array("id" => "1234"));
    
    $url = new ShippingEasy_SignedUrl($method, $path, $params, $json_body, "1390928206");
    $this->assertEqual($url->toString(), "https://app.shippingeasy.com/api/orders?foo=bar&xyz=123&api_key=XYZ123&api_timestamp=1390928206&api_signature=e690c72c971faf6088c9809460b3da56f047916b68526fb186a8759b76393f1b");
  } 
}
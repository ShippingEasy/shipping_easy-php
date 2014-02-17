<?php

class ShippingEasy_Signature
{
  public function __construct($api_secret=null, $http_method=null, $path=null, $params=null, $json_body=null)
  {
    $this->api_secret = $api_secret;
    $this->http_method = strtoupper($http_method);
    $this->path = $path;
    ksort($params);
    $this->params = $params;
    $this->json_body = json_encode($json_body);
  }

  public function getApiSecret()
  {
    return $this->api_secret;
  }

  public function getHttpMethod()
  {
    return $this->http_method;
  }

  public function getPath()
  {
    return $this->path;
  }

  public function getParams()
  {
    return $this->params;
  }

  public function getJsonBody()
  {
    return $this->json_body;
  }

  public function plaintext()
  {
    $parts = array($this->getHttpMethod());
    $parts[] = $this->getPath();
    $parts[] = http_build_query($this->getParams());

    if ($this->getJsonBody() != "null")
      $parts[] = $this->getJsonBody();

    return implode("&", $parts);
  }

  public function encrypted()
  {
    return hash_hmac('sha256', $this->plaintext(), $this->getApiSecret());
  }

  public function equals($signature)
  {
    return $this->encrypted() == $signature;
  }

}

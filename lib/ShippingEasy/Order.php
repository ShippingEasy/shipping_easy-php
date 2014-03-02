<?php

class ShippingEasy_Order extends ShippingEasy_Object
{
  public function __construct($store_api_key, $values) {
    $this->store_api_key = $store_api_key;
    $this->values = $values;
  }

  public function create()
  {
    $this->request("post", "/api/stores/$this->store_api_key/orders", null, array("order" => $this->values));
  }

}

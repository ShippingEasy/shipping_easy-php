<?php

class ShippingEasy_Cancellation extends ShippingEasy_Object
{

  public function __construct($store_api_key, $external_order_identifier) {
    $this->store_api_key = $store_api_key;
    $this->external_order_identifier = $external_order_identifier;
  }

  public function create()
  {
    return $this->request("post", "/api/stores/$this->store_api_key/orders/$this->external_order_identifier/cancellations");
  }

}

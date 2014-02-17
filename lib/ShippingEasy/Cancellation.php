<?php

class ShippingEasy_Cancellation extends ShippingEasy_Object
{

  public function __construct($external_order_identifier) {
    $this->external_order_identifier = $external_order_identifier;
  }

  public function create()
  {
    $this->request("post", "/api/orders/$this->external_order_identifier/cancellations");
  }

}

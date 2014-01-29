<?php

class ShippingEasy_Cancellation extends ShippingEasy_Object
{  
  public function create($external_order_identifier)
  {
    $this->request("post", "/api/orders/$external_order_identifier/cancellations");
  }
  
}
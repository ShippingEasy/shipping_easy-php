<?php

class ShippingEasy_Order extends ShippingEasy_Object
{  
  public function __construct($values) {
    $this->values = $values;
  }
    
  public function create()
  {
    $this->request("post", "/api/orders", null, array("order" => $this->values));
  }

}

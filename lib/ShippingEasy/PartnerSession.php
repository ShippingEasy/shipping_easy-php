<?php

class ShippingEasy_PartnerSession extends ShippingEasy_Object
{
  public function create($data = array())
  {
    return $this->request("post", "/partners/api/sessions", null, array("session" => $data), ShippingEasy::$partnerApiKey, ShippingEasy::$partnerApiSecret);
  }
}

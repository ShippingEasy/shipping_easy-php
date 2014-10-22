<?php

class ShippingEasy_PartnerAccount extends ShippingEasy_Object
{
  public function create($data = array())
  {
    return $this->request("post", "/partners/api/accounts", null, array("account" => $data), ShippingEasy::$partnerApiKey, ShippingEasy::$partnerApiSecret);
  }
}

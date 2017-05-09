<?php

class ShippingEasy_Order extends ShippingEasy_Object
{
  public function __construct($store_api_key=null, $values=null) {
    $this->store_api_key = $store_api_key;
    $this->values = $values;
  }

  public function create()
  {
    return $this->request("post", "/api/stores/$this->store_api_key/orders", null, array("order" => $this->values));
  }

  public function updateRecipient($external_order_id, $recipient_data)
  {
    return $this->request("put", "/api/stores/$this->store_api_key/orders/$external_order_id/recipient", null, array("recipient" => $recipient_data));
  }

  public function updateStatus($external_order_id, $new_status)
  {
      return $this->request("put", "/api/stores/$this->store_api_key/orders/$external_order_id/status", null, array("order" => array("order_status" => $new_status)));
  }

  public function find($id)
  {
    return $this->request("get", "/api/orders/$id");
  }

  public function findByStore($external_order_id)
  {
      return $this->request("get", "/api/stores/$this->store_api_key/orders/$external_order_id");
  }

  public function findAllByStore($params=array())
  {
    return $this->request("get", "/api/stores/$this->store_api_key/orders", $params);
  }

  public function findAll($params=array())
  {
    return $this->request("get", "/api/orders", $params);
  }
}

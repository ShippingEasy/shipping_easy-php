<?php

class ShippingEasy_ErrorTest extends UnitTestCase
{
  public function testCreation()
  {
    try {
      throw new ShippingEasy_Error("hello", 500, "{'foo':'bar'}", array('foo' => 'bar'));
      $this->fail("Did not raise error");
    } catch (ShippingEasy_Error $e) {
      $this->assertEqual("hello", $e->getMessage());
      $this->assertEqual(500, $e->getHttpStatus());
      $this->assertEqual("{'foo':'bar'}", $e->getHttpBody());
      $this->assertEqual(array('foo' => 'bar'), $e->getJsonBody());
    }
  }
}
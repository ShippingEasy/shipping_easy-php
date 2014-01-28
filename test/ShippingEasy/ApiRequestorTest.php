<?php

class ShippingEasy_ApiRequestorTest extends UnitTestCase
{
  public function testEncode()
  {
    $a = array('my' => 'value', 'that' => array('your' => 'example'), 'bar' => 1, 'baz' => null);
    $enc = ShippingEasy_APIRequestor::encode($a);
    $this->assertEqual($enc, 'my=value&that%5Byour%5D=example&bar=1');

    $a = array('that' => array('your' => 'example', 'foo' => null));
    $enc = ShippingEasy_APIRequestor::encode($a);
    $this->assertEqual($enc, 'that%5Byour%5D=example');

    $a = array('that' => 'example', 'foo' => array('bar', 'baz'));
    $enc = ShippingEasy_APIRequestor::encode($a);
    $this->assertEqual($enc, 'that=example&foo%5B%5D=bar&foo%5B%5D=baz');

    $a = array('my' => 'value', 'that' => array('your' => array('cheese', 'whiz', null)), 'bar' => 1, 'baz' => null);
    $enc = ShippingEasy_APIRequestor::encode($a);
    $this->assertEqual($enc, 'my=value&that%5Byour%5D%5B%5D=cheese&that%5Byour%5D%5B%5D=whiz&bar=1');
  }

  public function testUtf8()
  {
    // UTF-8 string
    $x = "\xc3\xa9";
    $this->assertEqual(ShippingEasy_ApiRequestor::utf8($x), $x);

    // Latin-1 string
    $x = "\xe9";
    $this->assertEqual(ShippingEasy_ApiRequestor::utf8($x), "\xc3\xa9");

    // Not a string
    $x = TRUE;
    $this->assertEqual(ShippingEasy_ApiRequestor::utf8($x), $x);
  }


}

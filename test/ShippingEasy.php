<?php

echo "Running the ShippingEasy PHP bindings test suite.\n".
     "If you're trying to use the ShippingEasy PHP bindings you'll probably want ".
     "to require('lib/ShippingEasy.php'); instead of this file\n";

function authorizeFromEnv()
{
  $apiKey = getenv('SHIPPINGEASY_API_KEY');
  if (!$apiKey)
    $apiKey = "tGN0bIwXnHdwOa85VABjPdSn8nWY7G7I";
  ShippingEasy::setApiKey($apiKey);
}

$ok = @include_once(dirname(__FILE__).'/simpletest/autorun.php');
if (!$ok) {
  $ok = @include_once(dirname(__FILE__).'/../vendor/vierbergenlars/simpletest/autorun.php');
}
if (!$ok) {
  echo "MISSING DEPENDENCY: The ShippingEasy API test cases depend on SimpleTest. ".
       "Download it at <http://www.simpletest.org/>, and either install it ".
       "in your PHP include_path or put it in the test/ directory.\n";
  exit(1);
}

// Throw an exception on any error
function exception_error_handler($errno, $errstr, $errfile, $errline) {
  throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}
set_error_handler('exception_error_handler');
error_reporting(E_ALL | E_STRICT);

require_once(dirname(__FILE__) . '/../lib/ShippingEasy.php');

require_once(dirname(__FILE__) . '/ShippingEasy/TestCase.php');

require_once(dirname(__FILE__) . '/ShippingEasy/ApiRequestorTest.php');
require_once(dirname(__FILE__) . '/ShippingEasy/AuthenticationErrorTest.php');
require_once(dirname(__FILE__) . '/ShippingEasy/Error.php');
require_once(dirname(__FILE__) . '/ShippingEasy/AuthenticatorTest.php');
require_once(dirname(__FILE__) . '/ShippingEasy/SignatureTest.php');
require_once(dirname(__FILE__) . '/ShippingEasy/SignedUrlTest.php');

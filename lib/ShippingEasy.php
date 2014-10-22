<?php

// Tested on PHP 5.2, 5.3

// This snippet (and some of the curl code) due to the Facebook SDK.
if (!function_exists('curl_init')) {
  throw new Exception('ShippingEasy needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('ShippingEasy needs the JSON PHP extension.');
}
if (!function_exists('mb_detect_encoding')) {
  throw new Exception('ShippingEasy needs the Multibyte String PHP extension.');
}

require(dirname(__FILE__) . '/ShippingEasy/ShippingEasy.php');

// Errors
require(dirname(__FILE__) . '/ShippingEasy/Error.php');
require(dirname(__FILE__) . '/ShippingEasy/ApiError.php');
require(dirname(__FILE__) . '/ShippingEasy/ApiConnectionError.php');
require(dirname(__FILE__) . '/ShippingEasy/AuthenticationError.php');
require(dirname(__FILE__) . '/ShippingEasy/InvalidRequestError.php');

require(dirname(__FILE__) . '/ShippingEasy/ApiRequestor.php');
require(dirname(__FILE__) . '/ShippingEasy/Authenticator.php');
require(dirname(__FILE__) . '/ShippingEasy/Object.php');
require(dirname(__FILE__) . '/ShippingEasy/Order.php');
require(dirname(__FILE__) . '/ShippingEasy/PartnerSession.php');
require(dirname(__FILE__) . '/ShippingEasy/PartnerAccount.php');
require(dirname(__FILE__) . '/ShippingEasy/Signature.php');
require(dirname(__FILE__) . '/ShippingEasy/SignedUrl.php');
require(dirname(__FILE__) . '/ShippingEasy/Cancellation.php');
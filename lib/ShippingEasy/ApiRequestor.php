<?php

class ShippingEasy_ApiRequestor
{
  public $apiKey;
  public $apiSecret;

  public static function utf8($value)
  {
    if (is_string($value) && mb_detect_encoding($value, "UTF-8", TRUE) != "UTF-8")
      return utf8_encode($value);
    else
      return $value;
  }

  public static function encode($arr, $prefix=null)
  {
    if (!is_array($arr))
      return $arr;

    $r = array();
    foreach ($arr as $k => $v) {
      if (is_null($v))
        continue;

      if ($prefix && $k && !is_int($k))
        $k = $prefix."[".$k."]";
      else if ($prefix)
        $k = $prefix."[]";

      if (is_array($v)) {
        $r[] = self::encode($v, $k, true);
      } else {
        $r[] = urlencode($k)."=".urlencode($v);
      }
    }

    return implode("&", $r);
  }

  public function request($meth, $path, $params=null, $payload = null, $apiKey = null, $apiSecret = null)
  {
    list($rbody, $rcode) = $this->_requestRaw($meth, $path, $params, $payload, $apiKey, $apiSecret);
    $resp = $this->_interpretResponse($rbody, $rcode);
    return $resp;
  }

  public function handleApiError($rbody, $rcode, $resp)
  {

    if (!is_array($resp) || !isset($resp['errors']))
      throw new ShippingEasy_ApiError("Invalid response object from API: $rbody (HTTP response code was $rcode)", $rcode, $rbody, $resp);

    $error = $resp['errors'];
    $message = isset($error[0]['message']) ? $error[0]['message'] : null;

    switch ($rcode) {
    case 400:
      throw new ShippingEasy_InvalidRequestError(json_encode($error), $rcode, $rbody, $resp);
    case 404:
      throw new ShippingEasy_InvalidRequestError($message, $rcode, $rbody, $resp);
    case 401:
      throw new ShippingEasy_AuthenticationError($message, $rcode, $rbody, $resp);
    default:
      throw new ShippingEasy_ApiError($message, $rcode, $rbody, $resp);
    }
  }

  private function _requestRaw($http_method, $path, $params, $payload, $apiKey, $apiSecret)
  {
    $url = new ShippingEasy_SignedUrl($http_method, $path, $params, $payload, null, $apiKey, $apiSecret);
    $absUrl = $url->toString();

    $langVersion = phpversion();
    $uname = php_uname();

    $ua = array('bindings_version' => ShippingEasy::VERSION,
		            'lang' => 'php',
            		'lang_version' => $langVersion,
            		'publisher' => 'ShippingEasy',
            		'uname' => $uname);

    $headers = array('X-ShippingEasy-Client-User-Agent: ' . json_encode($ua),
                     'User-Agent: ShippingEasy/v1 PhpBindings/' . ShippingEasy::VERSION,
                     'Authorization: Bearer ' . $apiKey);

    if (ShippingEasy::$apiVersion)
      $headers[] = 'ShippingEasy-Version: ' . ShippingEasy::$apiVersion;

    list($rbody, $rcode) = $this->_curlRequest($http_method, $absUrl, $headers, $payload);
    return array($rbody, $rcode);
  }

  private function _interpretResponse($rbody, $rcode)
  {
    try {
      $resp = json_decode($rbody, true);
    } catch (Exception $e) {
      throw new ShippingEasy_ApiError("Invalid response body from API: $rbody (HTTP response code was $rcode)", $rcode, $rbody);
    }
    if ($rcode < 200 || $rcode >= 300) {
      $this->handleApiError($rbody, $rcode, $resp);
    }
    return $resp;
  }

  private function _curlRequest($meth, $absUrl, $headers, $payload)
  {
    $curl = curl_init();
    $meth = strtolower($meth);
    $opts = array();

    if ($meth == 'get') {
      $opts[CURLOPT_HTTPGET] = 1;
    } else if ($meth == 'post') {
      $opts[CURLOPT_POST] = 1;
      if ($payload)
        $payload = json_encode($payload);

      $headers[] = 'Content-Type: application/json';
      $headers[] = 'Content-Length: ' . strlen($payload);
      $opts[CURLOPT_POSTFIELDS] = $payload;
    } else if ($meth == 'put') {
      $opts[CURLOPT_CUSTOMREQUEST] = 'PUT';
      if ($payload)
        $payload = json_encode($payload);

      $headers[] = 'Content-Type: application/json';
      $headers[] = 'Content-Length: ' . strlen($payload);
      $opts[CURLOPT_POSTFIELDS] = $payload;
    } else if ($meth == 'delete')  {
      $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
      if (count($params) > 0) {
	      $encoded = self::encode($params);
	      $absUrl = "$absUrl?$encoded";
      }
    } else {
      throw new ShippingEasy_ApiError("Unrecognized method $meth");
    }

    $opts[CURLOPT_URL] = $absUrl;
    $opts[CURLOPT_RETURNTRANSFER] = true;
    $opts[CURLOPT_CONNECTTIMEOUT] = 30;
    $opts[CURLOPT_TIMEOUT] = 80;
    $opts[CURLOPT_FOLLOWLOCATION] = true;
    $opts[CURLOPT_MAXREDIRS] = 4;
    $opts[CURLOPT_POSTREDIR] = 1 | 2 | 4; // Maintain method across redirect for all 3XX redirect types
    $opts[CURLOPT_HTTPHEADER] = $headers;

    curl_setopt_array($curl, $opts);
    $rbody = curl_exec($curl);
    $errno = curl_errno($curl);

    if ($rbody === false) {
      $errno = curl_errno($curl);
      $message = curl_error($curl);
      curl_close($curl);
      $this->handleCurlError($errno, $message);
    }

    $rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    return array($rbody, $rcode);
  }

  public function handleCurlError($errno, $message)
  {
    $apiBase = ShippingEasy::$apiBase;
    switch ($errno) {
    case CURLE_COULDNT_CONNECT:
    case CURLE_COULDNT_RESOLVE_HOST:
    case CURLE_OPERATION_TIMEOUTED:
      $msg = "Could not connect to ShippingEasy ($apiBase).  Please check your internet connection and try again.  If this problem persists, let us know at support@shippingeasy.com.";
      break;
    case CURLE_SSL_CACERT:
    case CURLE_SSL_PEER_CERTIFICATE:
      $msg = "Could not verify ShippingEasy's SSL certificate.  Please make sure that your network is not intercepting certificates.  (Try going to $apiBase in your browser.)  If this problem persists, let us know at support@shippingeasy.com.";
      break;
    default:
      $msg = "Unexpected error communicating with ShippingEasy.  If this problem persists, let us know at support@shippingeasy.com.";
    }

    $msg .= "\n\n(Network error [errno $errno]: $message)";
    throw new ShippingEasy_ApiConnectionError($msg);
  }
}

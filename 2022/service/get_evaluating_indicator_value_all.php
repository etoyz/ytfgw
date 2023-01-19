<?php
$url = $_SERVER['HTTP_HOST'] . str_replace("get_evaluating_indicator_value_all.php", "get_evaluating_indicator_value.php", $_SERVER['PHP_SELF']);

$params = '?' . http_build_query($_GET) . "&type=0";

$redirect_url = $url . $params;

//$options = array(
////    CURLOPT_RETURNTRANSFER => true,   // return web page
//    CURLOPT_HEADER         => false,  // don't return headers
//    CURLOPT_FOLLOWLOCATION => true,   // follow redirects
//    CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
//    CURLOPT_ENCODING       => "",     // handle compressed
//    CURLOPT_USERAGENT      => "test", // name of client
//    CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
//    CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
//    CURLOPT_TIMEOUT        => 120,    // time-out on response
//);

$ch = curl_init($redirect_url);

//curl_setopt_array($ch, $options);
var_dump($redirect_url);
$response = curl_exec($ch);

var_dump($response);

if (!isset($response))
    return null;
return $response;

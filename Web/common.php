<?php
require('db.php');
require('ip2locationlite.class.php');
require('lzwc.php');

$BASE_URL = 'http://www.urbanopticon.org/';

function getID(){
  $location = getLocationCookie();
  $ip = $location['ipAddress'];
  $city = $location['cityName'];
  $country = $location['countryCode'];
  $lat = $location['latitude'];
  $lon = $location['longitude'];

  connect();
  $q = "INSERT INTO users (ip,ip_city,ip_country,ip_lat,ip_lon) VALUE('$ip','$city','$country',$lat,$lon)";
  mysql_query($q);
  $user['id'] = mysql_insert_id();
  $data = base64_encode(serialize($user));
  setcookie('user', $data, time()+3600*24*30*12); //set cookie for 20 years))
  return $user;
}

function getIDCookie() {
  if(!$_COOKIE['user']){
    $user = getID();
  }
  else {
    $user = unserialize(base64_decode($_COOKIE["user"]));
  }
  return $user;
}

function getIP(){
  if (!empty($_SERVER['HTTP_CLIENT_IP']))  //check ip from share internet
    $ip=$_SERVER['HTTP_CLIENT_IP'];
  elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
  else
    $ip=$_SERVER['REMOTE_ADDR'];
  
  if ($ip == '127.0.0.1' or $ip == '::1')
    return '62.49.66.12';
  else
  return $ip;
}

function getLocation(){
  $ip = getIP();
  $ipLite = new ip2location_lite;
  $ipLite->setKey('ip_lite_key');
  
  $visitorGeolocation = $ipLite->getCity($ip);
  return $visitorGeolocation;
}

function getLocationCookie(){
  $ip = getIP();
  //Set geolocation cookie
  if(!$_COOKIE['geolocation']){
    $visitorGeolocation = getLocation();
    if ($visitorGeolocation['statusCode'] == 'OK') {
      $data = base64_encode(serialize($visitorGeolocation));
      setcookie('geolocation', $data, time()+3600*24); //set cookie for 1 day
    }
  }else{
    $visitorGeolocation = unserialize(base64_decode($_COOKIE["geolocation"]));
    if($visitorGeolocation['ipAddress'] != $ip){
      $visitorGeolocation = getLocation();
      if ($visitorGeolocation['statusCode'] == 'OK') {
        $data = base64_encode(serialize($visitorGeolocation));
        setcookie('geolocation', $data, time()+3600*24); //set cookie for 1 day
      }
    }
  }
  return $visitorGeolocation;
}

?>

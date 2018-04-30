<?php
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('America/Los_Angeles');


$firstName = $_GET["firstName"];
$lastName = $_GET["lastName"];
$homePhone = $_GET["homePhone"];
$primaryEmail= $_GET["primaryEmail"];
$street = $_GET["street"];
$city = $_GET["city"];
$zip = $_GET["zip"];
$stateCode = $_GET["stateCode"];



$ch = curl_init( "https://uatstream.caliberhomeloans.com/origination/pre-application/lead/v1/source/tj-767326/" );
# Setup request to send json via POST.
$payload = json_encode(  
  array("borrower"=>array(  
      "firstName"=>$firstName,
      "lastName"=>$lastName ,
      "homePhone"=>$homePhone ,
      "primaryEmail"=>$primaryEmail,
     "mailingAddress"=>array(
      "street"=>$street,
      "city"=>$city,
      "zip"=>$zip,
      "stateCode"=>$stateCode))));


curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
# Return response instead of printing.
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
# Send request.
$result = curl_exec($ch);
curl_close($ch);
# Print response.
echo "<pre>$result</pre>";




?>
<?php


date_default_timezone_set('America/Chicago');
ini_set("memory_limit", "16M");



$playerid="";
$firstName="";
$lastName="";
$cellPhoneNumber="";
$emailAddress="";
$t=time();



$DeliveredDate = date("M d Y H:i:s");
if ($_POST) {

var_dump($_POST);
file_put_contents('data.txt', $_POST, FILE_APPEND);
foreach ($_POST as $key => $value) {

//echo htmlspecialchars($key)."=".htmlspecialchars($value)."<br>";
$playerid=htmlspecialchars($_POST["playerid"]);

$firstName=htmlspecialchars($_POST["firstName"]);
$lastName=htmlspecialchars($_POST["lastName"]);
$cellPhoneNumber=htmlspecialchars($_POST["cellPhoneNumber"]);

$emailAddress=htmlspecialchars($_POST["emailAddress"]);

}


}


$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "http://24.234.49.5:8087/mgt/restservices/promotions/thirdpartypromotion",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_SSL_VERIFYPEER=>false,
CURLOPT_SSL_VERIFYHOST=>false,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\"playerID\": $playerid,\"mgtPromoID\": 64,\"mgtTierID\": 1, \"osRecordID\": $t, \"startDate\": \"4-17-2018\", \"endDate\": \"5-31-2018\",\"osOfferCode\": \"123\",\"osPrizeIDText\": \"$10 Free Play\", \"osPrizeIDValue\": 10, \"batchID\": 1,\"vendorKey\": \"JkIPcpwAf2WCyeh9rl8176l0KzWeX6ddeECZOA8qTbI=\",\"campaignID\": 0,\"segmentID\": 0}",
  CURLOPT_HTTPHEADER => array(
    "authorizationkey: 2fc5f429-290f-4b74-8301-3d0753499cf3",
    "cache-control: no-cache",
    "content-type: application/json",
    "partnerid: Api_201608242018181369",
    "postman-token: 6b46e52a-1218-f528-8995-f32c2b4dc70f",
  "licensekey: 84ED5649-6F02-4636-B269-5213D9AE5775"
  ),
));

$response = curl_exec($curl);
echo $response;
file_put_contents('data.txt', $response, FILE_APPEND);
$err = curl_error($curl);
echo $err ;
curl_close($curl);

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://24.234.49.5:8087/mgt/restservices/campaigns/players/$playerid/cell/optin/4",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_SSL_VERIFYPEER=>false,
CURLOPT_SSL_VERIFYHOST=>false,
  CURLOPT_CUSTOMREQUEST => "PUT",
  CURLOPT_POSTFIELDS => "",
  CURLOPT_HTTPHEADER => array(
    "authorizationkey: 2fc5f429-290f-4b74-8301-3d0753499cf3",
    "cache-control: no-cache",
    "content-type: application/json",
    "partnerid: Api_201608242018181369",
    "postman-token: 6b46e52a-1218-f528-8995-f32c2b4dc70f",
  "licensekey: 84ED5649-6F02-4636-B269-5213D9AE5775"
  ),
));

$response = curl_exec($curl);
echo $response;
file_put_contents('data.txt', $response, FILE_APPEND);
$err = curl_error($curl);
echo $err ;
curl_close($curl);


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://24.234.49.5:8087/mgt/restservices/player/$playerid/insertinfo",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_SSL_VERIFYPEER=>false,
CURLOPT_SSL_VERIFYHOST=>false,
  CURLOPT_CUSTOMREQUEST => "PUT",
  CURLOPT_POSTFIELDS => "{
 \"firstName\": \"$firstName\",
\"lastName\": \"$lastName\",
\"cellPhoneNumber\": \"$cellPhoneNumber\",
 \"emailAddress\": \"$emailAddress\"}",
  CURLOPT_HTTPHEADER => array(
    "authorizationkey: 2fc5f429-290f-4b74-8301-3d0753499cf3",
    "cache-control: no-cache",
    "content-type: application/json",
    "partnerid: Api_201608242018181369",
    "postman-token: 6b46e52a-1218-f528-8995-f32c2b4dc70f",
  "licensekey: 84ED5649-6F02-4636-B269-5213D9AE5775"
  ),
));

$response = curl_exec($curl);
echo $response;
file_put_contents('data.txt', $response, FILE_APPEND);
$err = curl_error($curl);
echo $err ;
curl_close($curl);



?>
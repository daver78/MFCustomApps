<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
date_default_timezone_set('America/Los_Angeles');
header('Access-Control-Allow-Headers: Content-Type');
$filename = 'inboundCC.csv';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

$data = file_get_contents('php://input');
var_dump($data);


$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://studio.afw.mdl.io/api/OutboundApp/AppCallback?serviceTypeId=2019&identifier=15171_598_636568116653905816&accountId=15171&outboundId=18",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_SSL_VERIFYPEER=>false,
CURLOPT_SSL_VERIFYHOST=>false,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $data,
  CURLOPT_HTTPHEADER => array(
    "authorizationkey: 2fc5f429-290f-4b74-8301-3d0753499cf3",
    "cache-control: no-cache",
    "content-type: application/json",
    "partnerid: Api_201608242018181369",
    "postman-token: 6b46e52a-1218-f528-8995-f32c2b4dc70f",
  "licensekey: ACD5B97E-F4D4-460E-A651-EF1C1E32D4E4"
  ),
));

$response = curl_exec($curl);
echo $response;
fwrite($myfile, $response);
$err = curl_error($curl);
echo $err ;
curl_close($curl);

}
?>






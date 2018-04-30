<?php
date_default_timezone_set('America/Chicago');
header('Access-Control-Allow-Origin: *');
header("Cache-Control: no-cache, must-revalidate");


$email = "kdutta@mindfireinc.com" ; // studio login		
$pwd = "MindFire2012"  ; // studio password
$PartnerGuid = "df83eb61_8630_483e_9432_5009c4c7c847"  ; //studio PartnerGuid
$PartnerPassword ="783FAaa9-0d41-45b3-b0d6-c8eBF8f143bd" ; //studio PartnerPassword
$SelectedAccountID = "4974";


$authRequest = array
(
	"SelectedAccountID" => $SelectedAccountID,
	"Email" => $email,
	"Password" => $pwd,
	"PartnerGuid" => $PartnerGuid,
	"PartnerPassword" => $PartnerPassword
);

$authResponse = callService("userservice/Authenticate", $authRequest);
$userTicket = $authResponse->{"Credentials"}->{"Ticket"};


$ErrorCode = $authResponse->{"Result"}->{"ErrorCode"};
if ($ErrorCode == "") {
} 


// ----------------------------------------- STUDIO part ------------------------------------------------

	$updateContact  = array
	(
		"Credentials" => array
		(
			"Ticket" => $userTicket
		),
		"KeyValueList" =>
			array(array("Key" => "mobile", "Value" => "17146061888"),array("Key" => "messagereplied", "Value" => "no"),array("Key" => "receivedtimestamp", "Value" => "no"),array("Key" => "keyword", "Value" => "no"))
		
	);


$update = callService("contactservice/updateContact", $updateContact);
$ErrorCode1 = $update->{"Result"}->{"ErrorCode"};

function callService($endpoint, $request)
{
    $request_string = json_encode($request);

    $service = curl_init('http://studio.mdl.io/REST/'.$endpoint);
    curl_setopt($service, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($service, CURLOPT_POSTFIELDS, $request_string);
    curl_setopt($service, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($service, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($request_string))
    );
    $response_string = curl_exec($service);

    $response = json_decode($response_string);
    return($response);
}

?>

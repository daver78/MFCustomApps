<?php
include 'config.php';
$file = 'testfile.txt';
$Message="";
$OriginatorAddress="";
$AcceptedTime="";
$CustomerNickname="";
if ($_POST) {    
echo htmlspecialchars($key)."=".htmlspecialchars($value)."<br>";
$Message=htmlspecialchars($_POST["Message"]);
$OriginatorAddress=htmlspecialchars($_POST["OriginatorAddress"]);
$OriginatorAddress=substr($OriginatorAddress,1);
$AcceptedTime=htmlspecialchars($_POST["AcceptedTime"]);
$CustomerNickname=htmlspecialchars($_POST["CustomerNickname"]);
}


date_default_timezone_set('America/Chicago');
header('Access-Control-Allow-Origin: *');
header("Cache-Control: no-cache, must-revalidate");

$current .= $OriginatorAddress.",".$Message."\n";
file_put_contents($file, $current);

	
$authRequest = array
(
	"SelectedAccountID" => $c_SelectedAccountID,
	"Email" => $c_email,
	"Password" => $c_pwd,
	"PartnerGuid" => $c_PartnerGuid,
	"PartnerPassword" => $c_PartnerPassword
);

$authResponse = callService("userservice/Authenticate", $authRequest);
$userTicket = $authResponse->{"Credentials"}->{"Ticket"};

file_put_contents($file, $userTicket );

$ErrorCode = $authResponse->{"Result"}->{"ExceptionMessage"};
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
			array(array("Key" => "mobile", "Value" => $OriginatorAddress),array("Key" => "$custom_field", "Value" => $Message),array("Key" => "$custom_field1", "Value" => $AcceptedTime),array("Key" => "$custom_field2", "Value" => $CustomerNickname))
		
	);

$update = callService("contactservice/updateContact", $updateContact);
$ErrorCode1 = $update->{"Result"}->{"ExceptionMessage"};

file_put_contents($file, $ErrorCode1);

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
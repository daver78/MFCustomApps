<?php

date_default_timezone_set('America/Chicago');
header('Access-Control-Allow-Origin: *');
header("Cache-Control: no-cache, must-revalidate");


// Get Params
$callback = $_GET['callback'];
$offercode = @$_GET['offercode'];
//$offercode = "17146061888";

$myfile = fopen("testfile.csv", "w");
fwrite($myfile, $offercode);
fclose($myfile);

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
} else {
	echo $callback, '(', json_encode( array('success'=>false, 'PURL'=>'')), ')';
	die();
}

$isExist = 0;

// ----------------------------------------- STUDIO part ------------------------------------------------
$filter = "<Filter CriteriaJoinOperator=\"&amp;\"><Criteria Row=\"1\" Field=\"mobile\" Operator=\"Equal\" Value=\"$offercode\" /></Filter>";



$FieldNames = array("PURL","FirstName");

$ContactListRequest   = array
(
	"Credentials" => array
	(
		"Ticket" => $userTicket
	),
	"FieldNames" => $FieldNames,
	"Filter" => $filter,
	"OutputType" => 1
);

	
$ContactListResponse = callService("contactservice/GetContactList", $ContactListRequest);

$ErrorCode = $ContactListResponse->{"Result"}->{"ErrorMessage"};




if ($ErrorCode == "") {
	$Contacts = $ContactListResponse->{"Contacts"};
	foreach ($Contacts as $chr) {
		$Contact .= chr($chr);
	}

	$FieldExport = 'PURL,FirstName';
	$FieldLength = strlen($FieldExport);
	$data = substr($Contact,$FieldLength,strlen($Contact));

	$data = trim($data);
	if ($data == '') {


	} else {
		$isExist = 1;
		$arr2 = explode(",", $data);
		$purl = $arr2[0];
	
		echo $callback, '(', json_encode( array('success'=>true, 'PURL'=>$purl)), ')';
		die();
	}
}

//echo $callback, '(', json_encode( array('success'=>true, 'PURL'=>$purl)), ')';



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

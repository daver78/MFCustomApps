<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');  



$callback = @$_GET['callback'];
$email = @$_GET['email'];
$playerid = @$_GET['playerid'];
$test = @$_GET['test'];

$PartnerEmail = @$_GET['p1'];
$PartnerPWD = @$_GET['p2'];
$PartnerGuid = @$_GET['p3'];
$PartnerPassword = @$_GET['p4'];
$SelectedAccountID = @$_GET['p5'];


//$PartnerEmail = "kdutta@mindfireinc.com" ; // studio login
//$PartnerPWD = "MindFire2012"  ; // studio password
//$PartnerGuid = "df83eb61_8630_483e_9432_5009c4c7c847"  ; //studio PartnerGuid
//$PartnerPassword ="783FAaa9-0d41-45b3-b0d6-c8eBF8f143bd" ; //studio PartnerPassword
//$SelectedAccountID = "15171";

$processDate = date("m/d/Y");


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


function getTicket($SelectedAccountID, $PartnerEmail, $PartnerPWD, $PartnerGuid, $PartnerPassword) {
	$authRequest = array
	(
		"SelectedAccountID" => $SelectedAccountID,
		"Email" => $PartnerEmail, 
		"Password" => $PartnerPWD, 
		"PartnerGuid" => $PartnerGuid, 
		"PartnerPassword" => $PartnerPassword
	);

	$authResponse = callService("userservice/Authenticate", $authRequest);

	$userTicket = $authResponse->{"Credentials"}->{"Ticket"};

	$ErrorCode = $authResponse->{"Result"}->{"ErrorCode"};
	if ($ErrorCode == "") {	
		
	} else {
		$errorMessage = "Authenticate ERROR : ".$authResponse->{"Result"}->{"ErrorMessage"};
		$userTicket = '';
	}

	return $userTicket;
}

function getContact($test, $userTicket, $email, $playerid) {
	$Contact = "";

	if ($test == 't') {
		echo "email : $email<br>\n";
		echo "playerid : $playerid<br>\n";
		echo "userTicket : $userTicket<br>\n";
	}

	$FieldNames = array("purl","optin");

	//echo "userTicket=$userTicket,emailData=$emailData,LastName=$LastName,opr1=$opr1,opr2=$opr2<br>";

	if ($playerid != '') {
		$Filter = "<Filter CriteriaJoinOperator=\"|\"> <Criteria Row=\"1\" Field=\"playerid\" Operator=\"Equal\" Value=\"$playerid\" /></Filter>";	
	} else {
		$Filter = "<Filter CriteriaJoinOperator=\"|\"> <Criteria Row=\"1\" Field=\"email\" Operator=\"Equal\" Value=\"$email\" /></Filter>";	
	}

	if ($test == 't') {
		echo "Filter : $Filter<br>\n";
	}

	$ContactListRequest   = array
	(
		
		"Credentials" => array
		(
			"Ticket" => $userTicket        
		),
	
		"FieldNames" => $FieldNames,
		"Filter" => $Filter,
		"OutputType" => 1,
	);
	$ContactListResponse = callService("contactservice/GetContactList", $ContactListRequest);
	$ErrorCode = $ContactListResponse->{"Result"}->{"ErrorCode"};
	if ($test == 't') {
		echo "ErrorCode : $ErrorCode<br>\n";
	}
	if ($ErrorCode == "") {
		$Contacts = $ContactListResponse->{"Contacts"};
		
		foreach ($Contacts as $chr) {
			$Contact .= chr($chr);
		}
		//echo "Contact : $Contact<BR>";
		$pieces = explode("\r\n", $Contact);
		if ($pieces) {
			$Contact = $pieces[1];			
		}
		
	} else {
		$errorMessage = "ContactListResponse ERROR : <br> ErrorMessage -> ".$ContactListResponse->{"Result"}->{"ErrorMessage"}.'<br>'.
		"ExceptionMessage : ".$ContactListResponse->{"Result"}->{"ExceptionMessage"};
		if ($test == 't') {
			echo "errorMessage : $errorMessage<br>\n";
		}
	}

	return $Contact;
}


//$userTicket = "e0a700fd880f472498fb02c836c962e8"; // Fond-Du-Luth
$userTicket = getTicket($SelectedAccountID, $PartnerEmail, $PartnerPWD, $PartnerGuid, $PartnerPassword);

$optin = '';
$purl = '';
$Contact = getContact($test, $userTicket, $email, $playerid);


if ($test == 't') {
	echo "Contact : $Contact<br>\n";
}

if ($Contact == '') {
	$success = false;
} else {
	$success = true;
	$pieces = explode(",", $Contact);
	if ($pieces) {
		$purl = $pieces[0];
		$optin = $pieces[1];
	}
}


echo $callback, '(', json_encode( array('success'=>$success, 'optin'=>$optin, 'url'=>$purl)), ')';

?>

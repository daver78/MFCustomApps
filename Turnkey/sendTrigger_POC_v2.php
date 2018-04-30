<?php

$environment = "http://studio.mdl.io/"; 

$sDesignator = @$_GET['sDesignator'];
$sEventAddress = @$_GET['sEventAddress'];
$sEventID = @$_GET['sEventID'];
$sEventLocation = @$_GET['sEventLocation'];
$sEventState = @$_GET['sEventState'];
$sEventZip = @$_GET['sEventZip'];
$sGuestFirstName = @$_GET['sGuestFirstName'];
$sGuestLastName = @$_GET['sGuestLastName'];
$sMarketGroupCode = @$_GET['sMarketGroupCode'];
$sRegistrationDate = @$_GET['sRegistrationDate'];
$sSeminarDate = @$_GET['sSeminarDate'];
$sUniqueIdentifier = @$_GET['sUniqueIdentifier'];
$sEventCity = @$_GET['sEventCity'];
$sFirstName = @$_GET['sFirstName'];
$sLastName = @$_GET['sLastName'];
$sAddress1 = @$_GET['sAddress1'];
$sAddress2 = @$_GET['sAddress2'];
$sCity = @$_GET['sCity'];
$sState = @$_GET['sState'];
$sZip = @$_GET['sZip'];
$sCountry = @$_GET['sCountry'];
$sCellPhone = @$_GET['sCellPhone'];
$sHomePhone = @$_GET['sHomePhone'];
$sEmail = @$_GET['sEmail'];
$sInboundPhone = @$_GET['sInboundPhone'];
$sSeminarHours = @$_GET['sSeminarHours'];
$sSource = @$_GET['sSource'];
$sMarketGroupLocation = @$_GET['sMarketGroupLocation'];
$sSeminarDisplayDate = @$_GET['sSeminarDisplayDate'];
$sVIP = @$_GET['sVIP'];

//$sSeminarDisplayDate = substr($sSeminarDate,0,10);
//if ($sSeminarDisplayDate !== false) {

//} else {
//	$sSeminarDisplayDate = '';
//}
$sHotelCSZ = @$_GET['sHotelCSZ'];

$userTicket = "69a3354bd2944c4a8deaf03ff6e92932";

importData($environment, $userTicket, $sEmail, $sFirstName, $sLastName);

$addContact  = array // The new Contact array; I grabbed this from Dustin's email
 		(
			"Credentials" => array
			(
				"Ticket" => $userTicket
			), 
			"KeyValueList" => array(
				array("Key" => "Designator", "Value" => $sDesignator),
				array("Key" => "EventAddress", "Value" => $sEventAddress),
				array("Key" => "EventID", "Value" => $sEventID),
				array("Key" => "EventLocation", "Value" => $sEventLocation),
				array("Key" => "EventState", "Value" => $sEventState),
				array("Key" => "EventZip", "Value" => $sEventZip),
				array("Key" => "GuestFirstName", "Value" => $sGuestFirstName),
				array("Key" => "GuestLastName", "Value" => $sGuestLastName),
				array("Key" => "MarketGroupCode", "Value" => $sMarketGroupCode),
				array("Key" => "RegistrationDate", "Value" => $sRegistrationDate),
				array("Key" => "SeminarDate", "Value" => $sSeminarDate),
				array("Key" => "UniqueIdentifier", "Value" => $sUniqueIdentifier),
				array("Key" => "EventCity", "Value" => $sEventCity),
				array("Key" => "FirstName", "Value" => $sFirstName),
				array("Key" => "LastName", "Value" => $sLastName),
				array("Key" => "Address1", "Value" => $sAddress1),
				array("Key" => "Address2", "Value" => $sAddress2),
				array("Key" => "City", "Value" => $sCity),
				array("Key" => "Zip", "Value" => $sZip),
				array("Key" => "State", "Value" => $sState),
				array("Key" => "Country", "Value" => $sCountry),
				array("Key" => "CellPhone", "Value" => $sCellPhone),
				array("Key" => "HomePhone", "Value" => $sHomePhone),
				array("Key" => "Email", "Value" => $sEmail),
				array("Key" => "SeminarHours", "Value" => $sSeminarHours),
				array("Key" => "InboundPhone", "Value" => $sInboundPhone),
				array("Key" => "LeadSource", "Value" => $sSource),
				array("Key" => "MarketGroupLocation", "Value" => $sMarketGroupLocation),
				array("Key" => "SeminarDisplayDate", "Value" => $sSeminarDisplayDate),
				array("Key" => "VIP", "Value" => $sVIP),
				array("Key" => "HotelCSZ", "Value" => $sHotelCSZ)				
			)
		);
  
file_put_contents('dump.txt',  $_SERVER['REQUEST_URI'], FILE_APPEND);
print_r(json_encode($addContact)); // Take a look at our array
file_put_contents('dump.txt', json_encode($addContact), FILE_APPEND);




$newContact = callService($environment,"REST/contactservice/CreateContact", $addContact); // Add our new Contact; remember to handle the case if the Contact exists 

print_r($newContact);
file_put_contents('dump.txt', $newContact, FILE_APPEND);

$ErrorCode = $newContact->{"Result"}->{"ErrorCode"};
//print_r("newContact ErrorCode = $ErrorCode\n");
if ($ErrorCode == "") {
	$purl=$newContact->{'Purl'}; // The Contact's newly issued PURL
	// Dave added this since CURL doesn't seem to work on this server.  We use lynx instead.
	exec("lynx -dump http://".$purl.".flipping.m.mdl.io/sendTrigger.html", $return_var);
	var_dump($return_var);
	file_put_contents('dump.txt',$return_var, FILE_APPEND);


	
} else {
	$errorMessage = $newContact->{"Result"}->{"ExceptionMessage"};
	print_r("Added new contact error: $errorMessage\n<br>");
	fwrite($myfile, "Added new contact error: $errorMessage\n<br>");
file_put_contents('dump.txt',"Added new contact error: $errorMessage\n<br>", FILE_APPEND);

	$purl=$newContact->{'Purl'}; // The Contact's newly issued PURL
	print_r("Update contact with this PURL: $purl\n<br>");
	file_put_contents('dump.txt',"Update contact with this PURL: $purl\n<br>", FILE_APPEND);
	

	$updateContact = callService($environment,"REST/contactservice/UpdateContact", $addContact);
	$ErrorCode = $updateContact->{"Result"}->{"ErrorCode"};
	print_r("$ErrorCode\n<br>");
	file_put_contents('dump.txt',"$ErrorCode\n<br>", FILE_APPEND);
	if ($ErrorCode == "") {
		print_r("Update contact success\n<br>");
			fwrite($myfile,"Update contact success\n<br>");
		$purl= getPurl($environment, $userTicket, $sEmail, $sCellPhone);
		print_r("purl = $purl<br>\n");
		fwrite($myfile,"purl = $purl<br>\n");
file_put_contents('dump.txt',"purl = $purl<br>\n", FILE_APPEND);

		exec("lynx -dump http://".$purl.".flipping.m.mdl.io/sendTrigger.html", $return_var);

		var_dump($return_var);
	file_put_contents('dump.txt',$return_var, FILE_APPEND);
	} else {

		$errorMessage = $updateContact->{"Result"}->{"ExceptionMessage"};
		print_r("Update contact error: $errorMessage\n<br>");
		file_put_contents('dump.txt',"Update contact error: $errorMessage\n<br>", FILE_APPEND);

	}

}

function callService($environment, $endpoint, $request) // I copied this directly from the Client's email ...
{
    $request_string = json_encode($request);
    $service = curl_init($environment.$endpoint);                                                                    
    curl_setopt($service, CURLOPT_CUSTOMREQUEST, "POST");                                                                    
    curl_setopt($service, CURLOPT_POSTFIELDS, $request_string);                                                                 
    curl_setopt($service, CURLOPT_RETURNTRANSFER, true);  
    curl_setopt($service, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($service, CURLOPT_SSL_VERIFYPEER, 0);                                                                    
    curl_setopt($service, CURLOPT_HTTPHEADER, array(                                                                         
        'Content-Type: application/json',                                                                               
        'Content-Length: ' . strlen($request_string))                                                                      
    );                                                                                                                  
    $response_string = curl_exec($service);
$serviceLog = fopen("servicelog.txt", "a");
fwrite($serviceLog, "\r\n<br>----------------------------------\r\n<br>Called service " . $environment . $endpoint);
fwrite($serviceLog, "\r\n<br>Request string was $request_string");
fwrite($serviceLog, "\r\n<br>Got response: $response_string (end)");
    $response = json_decode($response_string);
fwrite($serviceLog, "\r\n<br>Decoded response: " . print_r($response, TRUE) . " (end)");
fclose($serviceLog);
    return($response);
		file_put_contents('dump.txt',$response, FILE_APPEND);
  
}


function importData($environment, $userTicket, $sEmail, $sFirstName, $sLastName) {
	
	$row = 1;
	sleep(1);
	$t = time();
	$importName = 'API_'.$t;

	$Mapping = $Mapping . 
	"[email][email];[firstname][firstname];[lastname][lastname];";
	
	$dataList = '"Email","FirstName","LastName"'.PHP_EOL;


	$dataList .= '"'.$sEmail.'","'.$sFirstName.'","'.$sLastName.'"'.PHP_EOL;
	
	$FileName = $importName.'.csv';
	$importFile = '';

	//file_put_contents(FOLDER.'/data/'.$FileName, $dataList);

	$byteArr = str_split($dataList);
	foreach ($byteArr as $key=>$val) { 
		$byteArr[$key] = ord($val); 
	}
	//var_dump($byteArr);
	 
	//Import List
	$ImportRequest   = array
	(
		"FileName" => $FileName,
		"Filterable" => true,
		"Mapping" => $Mapping,
		"Mode" => 5,
		"Name" => $importName,
		"NotificationEmail" => $email,
		"CSV" => $byteArr,
		"Credentials" => array
		(
			"Ticket" => $userTicket        
		),
		"CsvFormat" => 1
	);
	$ImportResponse = callService(environment,"REST/contactservice/ImportContacts", $ImportRequest);
	$ErrorCode = $ImportResponse->{"Result"}->{"ErrorCode"};
	//$ErrorCode = '';
	if ($ErrorCode == "") {
		
		//moveFile($importFile,$folder.'/backup/');
		print_r("importData : Success\n<br>");
			fwrite($myfile,"importData : Success\n<br>");
	
		
		//echo "importData : Success<br>\n";
	} else {
		$errorMessage = "ImportResponse ERROR : \n ErrorMessage -> ".$ImportResponse->{"Result"}->{"ErrorMessage"}."<br>\n".
		"ExceptionMessage : ".$ImportResponse->{"Result"}->{"ExceptionMessage"};
		print_r("importData : Fail : $errorMessage\n<br>");
		//$errorMessage = "ImportResponse ERROR : \n ErrorMessage -> ".$ImportResponse->{"Result"}->{"ErrorMessage"};
		//echo "importData : Fail : $errorMessage<br>\n";
		//moveFile($importFile,$folder.'/error/');
	}
	return $row-2;
		fwrite($myfile,$row-2);
	



}

function getFilter($sEmail,$sCellPhone) {
	$Filter = '';
	$JoinOperator = "";
	$CriteriaRow = "";

	$row = 0;

	$opr1 = 'Equal';
	$sCellPhone = '';

	//echo "Product=$Product,sCellPhone=$sCellPhone<br>";


	if ($sEmail != '') {
		$row++;
		$CriteriaRow1 .= "Criteria Row=\"$row\" Field=\"Email\" Operator=\"$opr1\" Value=\"$sEmail\" <br>";
		$CriteriaRow .= "<Criteria Row=\"$row\" Field=\"Email\" Operator=\"$opr1\" Value=\"$sEmail\" />";
		$JoinOperator = "$row";
	}	
	
	print_r("JoinOperator : $JoinOperator\n<br>CriteriaRow1 = $CriteriaRow1\n<br>");
	
	//echo "JoinOperator = $JoinOperator<br>CriteriaRow1 = $CriteriaRow1<br>";

	if ($row > 0) {
		$Filter = "<Filter CriteriaJoinOperator=\"$JoinOperator\">$CriteriaRow</Filter>";
	} else {
		$Filter = "<Filter CriteriaJoinOperator=\"&amp;\" />";		
	}
	
	
	return $Filter;
}

function getPurl($environment, $userTicket, $sEmail, $sCellPhone) {
	
	$Contact = "";
	$rows = array();

	$FieldNames = array("PURL");
	
	print_r("sEmail : $sEmail\n<br>");
	print_r("sCellPhone : $sCellPhone\n<br>");
	
	$Filter =  getFilter($sEmail,$sCellPhone);
	print_r("Filter : $Filter\n<br>");
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
	$ContactListResponse = callService($environment,"REST/contactservice/GetContactList", $ContactListRequest);
	$ErrorCode = $ContactListResponse->{"Result"}->{"ErrorCode"};
	print_r("GetContactList ErrorCode : $ErrorCode\n<br>");
	if ($ErrorCode == "") {
		$Contacts = $ContactListResponse->{"Contacts"};


		
		foreach ($Contacts as $chr) {
			$Contact .= chr($chr);
		}

		$Contact = trim(substr($Contact, 5));

		
		print_r("Contact : $Contact\n<br>");

	} else {
		$errorMessage = "ContactListResponse ERROR : <br> ErrorMessage -> ".$ContactListResponse->{"Result"}->{"ErrorMessage"}.'<br>'.
		"ExceptionMessage : ".$ContactListResponse->{"Result"}->{"ExceptionMessage"};
		//$errorMessage = "ImportResponse ERROR : <br> ErrorMessage -> ".$ImportResponse->{"Result"}->{"ErrorMessage"};
		//echo $errorMessage."<BR>";
		print_r("GetContactList errorMessage : $errorMessage\n<br>");
	}

	return $Contact;



}

?>

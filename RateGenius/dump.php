<?php
date_default_timezone_set('America/Chicago');
ini_set("memory_limit", "16M");
$myfile1 = fopen("testfile.csv", "a");

$AppID="";
$EventOption="";
$MessageType="";
$Subject="";
$DeliveredDate="";
$Name="";
$Message="";
$Action="";

$DeliveredDate = date("M d Y H:i:s");
if ($_POST) {
foreach ($_POST as $key => $value) {
//echo htmlspecialchars($key)."=".htmlspecialchars($value)."<br>";
$Message=htmlspecialchars($_POST["Message"]);
fwrite($myfile, $Message);
$myfile = fopen("testfile1.csv", "w");
$Message=base64_encode($Message);
$AppID=htmlspecialchars($_POST["AppID"]);
fwrite($myfile, $AppID);
$EventOption=htmlspecialchars($_POST["EventOption"]);
$MessageType=htmlspecialchars($_POST["MessageType"]);
$Subject=htmlspecialchars($_POST["Subject"]);
$Name=htmlspecialchars($_POST["Name"]);
$Action=htmlspecialchars($_POST["Action"]);
fwrite($myfile, $Action);
fclose($myfile);
}
}


$ch = curl_init( "https://studio.afw.mdl.io/api/OutboundApp/AppCallback?serviceTypeId=2019&identifier=2321_1528_636328641848028783&accountId=2321&outboundId=558" );
# Setup request to send json via POST.
$payload = json_encode(  
  array("request"=>array(  
      "AppID"=>$AppID,
      "EventOption"=>$EventOption,
      "MessageType"=>$MessageType,
      "Subject"=>$Subject,
      "DeliveredDate"=>$DeliveredDate,
      "Name"=>$Name,
      "Content"=>$Message,
      "Action"=>$Action,
      "Date"=>$DeliveredDate)));


curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
# Return response instead of printing.
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
# Send request.
$result = curl_exec($ch);
curl_close($ch);
# Print response.
echo "<pre>$result</pre>";

fwrite($myfile1, $AppID);
fwrite($myfile1, "  |  ");
fwrite($myfile1, $MessageType);
fwrite($myfile1, "  |  ");
fwrite($myfile1, $EventOption);
fwrite($myfile1, "  |  ");
fwrite($myfile1, $Subject);
fwrite($myfile1, "  |  ");
fwrite($myfile1, $Action);
fwrite($myfile1,  "\n\n");
fwrite($myfile1, $payload);
fwrite($myfile1,  "\n\n");
fwrite($myfile1, $result);
fwrite($myfile1, "\n--------------------\n");

fclose($myfile1);


?>
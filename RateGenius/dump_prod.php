<?php
date_default_timezone_set('America/Chicago');
ini_set("memory_limit", "16M");
$myfile = fopen("testfile.csv", "a");

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
$AppID=htmlspecialchars($_POST["AppID"]);
fwrite($myfile, $AppID);
$EventOption=htmlspecialchars($_POST["EventOption"]);
$MessageType=htmlspecialchars($_POST["MessageType"]);
$Subject=htmlspecialchars($_POST["Subject"]);
$Name=htmlspecialchars($_POST["Name"]);
$Action=htmlspecialchars($_POST["Action"]);
if ($Action == "Contact Opted Out") {
    $Message=base64_encode($Action);
fwrite($myfile, $Action);
fwrite($myfile, $Message);
}
else {
fwrite($myfile, $Action);
$Message=htmlspecialchars($_POST["Message"]);
$Message=base64_encode($Message);
}
fclose($myfile);
}
}


$ch = curl_init( "https://api.rategenius.com/v1/rgsystems/users/receiveMindFireNotification" );
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
fclose($myfile);


?>
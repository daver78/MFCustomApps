<?php

$check = $_GET['check'];
if ($check=="true")
{
 echo "OK";
}
else

{
echo "Not OK";
}

$file = 'testfile.txt';
$Message="";
$OriginatorAddress="";
$AcceptedTime="";
$CustomerNickname="";
if ($_POST) {    

$Message=htmlspecialchars($_POST["Message"]);
$OriginatorAddress=htmlspecialchars($_POST["OriginatorAddress"]);
$OriginatorAddress=substr($OriginatorAddress,1);
$AcceptedTime=htmlspecialchars($_POST["AcceptedTime"]);
$CustomerNickname=htmlspecialchars($_POST["CustomerNickname"]);
$Key=strtolower($CustomerNickname);
$splitstrings = explode(" ", $Message);
$subkeyword=$splitstrings[1];
$subKey=strtolower($subkeyword);

}


header('Access-Control-Allow-Origin: *');
header("Cache-Control: no-cache, must-revalidate");

$servername = "cd.com-ext.com";
$username = "bondadmin";
$password = "auto!23$";
$dbname="inboundSMS_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password,$dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


if (strlen($subkeyword) == 0)
{
$sql = "select Keyword,Identifier,accountid,outboundId,contactfieldvalue,eventid from dataStudioEP where lower(Keyword)='". $Key. "'" ."and subkeyword is null";
echo $sql;

}
else
{
$sql = "select Keyword,subkeyword,Identifier,accountid,outboundId,contactfieldvalue,eventid from dataStudioEP where lower(Keyword)='". $Key. "'" ."and lower(subkeyword)='".$subKey. "'";

}

//$result = $conn->mysqli_query($sql);
$result = $conn->query($sql);



if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      $var="https://studio.afw.mdl.io/api/OutboundApp/AppCallback?serviceTypeId=2019&identifier=". $row["Identifier"]."&accountId=". $row["accountid"]."&outboundId=". $row["outboundId"];

if (strpos(strtolower($Message),  strtolower($row["Keyword"])) !== false) {
    
 
// Setup request to send json via POST.
$payload = json_encode(  
  array("request"=>array(  
      "optin"=>$row["contactfieldvalue"],
      "mobile"=>$OriginatorAddress,
      "eventid"=> $row["eventid"],
      "date"=>$date,
      "eventoption"=>$OriginatorAddress."-".$Message,
      "dedup"=>"mobile::".$OriginatorAddress
     )));

}

 //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

// create curl resource 
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, $var); 
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   
 




 }
} else {
   
}
$conn->close();







?>
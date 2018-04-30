<?php

date_default_timezone_set('America/Los_Angeles');
header('content-type: text/ecmascript');

$dom   = $_GET['dom'];  // Get Cookie name
$id    = $_GET['id']; // Get Identifier
$aid   = $_GET['aid']; // Get Account Id
$oid   = $_GET['oid']; // Get Outbound contact id
$pid   = $_GET['pid']; // Get purl
$track = $_GET['track']; // Flag determines to read the cookie

parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY), $queries);


// Set cookie if purl is not null
if ($pid != null) {
    
  $url=$queries['URL'];
$parts = parse_url($url);
parse_str($parts['query'], $query);
$utm= $query['utm_medium']. " " .$query['utm_source']. " " .$query['utm_campaign']. " " .$query['utm_term'];
    
    $uid = $id . " " . $aid . " " . $oid . " " . $pid. " " . $queries['DV_CampaignID']. " " . $queries['DV_outboundElementID']. " " . $queries['DV_scheduleID']. " " .$utm;
    
    setcookie($dom, $uid, time() + 60 * 60 * 24 * 120);
    
    $status = "// Cookie Added/Updated\n";
    
    echo $status;
    
}

// Read cookie 
else if ($track == "true") {
    
    recognize_cookie($dom);
    
    $status ="// Previous vistor recognized (COOKIE)\n";
    
    echo $status;
 
}

// This portion checks CallBack URL

else if ($track == "false") {
    

  $var = "http://studio.afw.mdl.io/api/OutboundApp/AppCallback?serviceTypeId=2019&identifier=" . $id . "&accountId=" . $aid . "&outboundId=" . $oid;
 

    // Setup request to send json via POST.
    $payload = json_encode(array(
     "eventoption" =>    "dump"    ));
    
    $status ="// Check Callback URL\n";

send_post($var,$payload);
    
}
else {
    
   
    
}


// Function which sends Tracking Data to Studio for Page Visit
function recognize_cookie($dom,$id,$aid,$oid)
{
    
    $pieces = explode(" ", $_COOKIE[$dom]);
    $id  = $pieces[0];
    $aid = $pieces[1];
    $oid = $pieces[2];
    $pid = $pieces[3];
    $DV_CampaignID = $pieces[4];
    $DV_outboundElementID = $pieces[5];
    $DV_scheduleID = $pieces[6];
    $utmParam='utm_medium='.$pieces[7].'|utm_source='.$pieces[8].'|utm_campaign='.$pieces[9]."".$pieces[10].'|utm_term='.$pieces[11];;

    $x = parse_url($_SERVER['HTTP_REFERER']);
    $Json =$_SERVER['HTTP_X_FORWARDED_FOR'].'|'.$DV_CampaignID.'|'.$DV_outboundElementID.'|'.$DV_scheduleID.'|'.$x['path'];


  $var = "http://studio.afw.mdl.io/api/OutboundApp/AppCallback?serviceTypeId=2019&identifier=" . $id . "&accountId=" . $aid . "&outboundId=" . $oid;
   

    // Setup request to send json via POST.
    $payload = json_encode(array(
     "eventoption" =>    substr( $Json,-128) ,
        "dedup" => $pid
    ));
  send_post($var,$payload);

    $payload1 = json_encode(array(
     "eventoption" =>    "dump"    ));

if($aid=="")
{
$aid=preg_replace("/[^0-9]/", '', $dom);
}


$var1 = "https://davinci.mindfireinc.com/admin/saveConversion2.php?purl=".$pid."&ipaddress=".$_SERVER['HTTP_X_FORWARDED_FOR']."&accountid=". $aid ."&programid=".$DV_CampaignID."&elementname=".$DV_outboundElementID."&visitpage=".$x['path']."&urlParam=".$utmParam."&visittime=".date("Y-m-d").'/'.date("H:i:s");

//$var1 = "http://davincistage.mindfireis.com/admin/saveConversion2.php?purl=".$pid."&ipaddress=".$_SERVER['HTTP_X_FORWARDED_FOR']."&accountid=". $aid ."&programid=".$DV_CampaignID."&elementname=".$DV_outboundElementID."&visitpage=".$x['path']."&urlParam=".$utm_param."&visittime=".date("Y-m-d").'/'.date("H:i:s");
send_post($var1,$payload1);

}


function send_post($var,$payload)
{

    // create curl resource 
    $ch = curl_init();
    
    // set url 
    curl_setopt($ch, CURLOPT_URL, $var);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type:application/json'
    ));

    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    // $output contains the output string 
    $output = curl_exec($ch); 


    $response="{\"Response\":\"Congrats, this is your response!\"}";


if(strcmp($output,$response))
{
   echo "{\"Response\":\"Connection_Failure!\"}";  
}
else
{
   echo "{\"Response\":\"Connection_Successful!\"}";
}

    // close curl resource to free up system resources 
    curl_close($ch);       
}


?>
 

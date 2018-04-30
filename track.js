<?php

date_default_timezone_set('America/Los_Angeles');
header('content-type: text/ecmascript');

$dom   = $_GET['dom'];  // Get Cookie name
$id    = $_GET['id']; // Get Identifier
$aid   = $_GET['aid']; // Get Account Id
$oid   = $_GET['oid']; // Get Outbound contact id
$pid   = $_GET['pid']; // Get purl
$track = $_GET['track']; // Flag determines to read the cookie

// Set cookie if purl is not null
if ($pid != null) {
    
    $uid = $id . " " . $aid . " " . $oid . " " . $pid;
    
    setcookie($dom, $uid, time() + 60 * 60 * 24 * 120);
    
    $status = "// Cookie Added/Updated\n";
    
    echo $status;
    
}

// Read cookie 
else if ($track == true && $_COOKIE[$dom] != null) {
    
    recognize_cookie($dom);
    
    $status ="// Previous vistor recognized (COOKIE)\n";
    
    echo $status;
    
}

// This portion sets cookie when website is clicked via email with required details
else {
    
    $query  = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY);
    $params = array();
    parse_str($query, $params);
    $id  = $params['id'];
    $aid = $params['aid'];
    $oid = $params['oid'];
    $pid = $params['pid'];
    if ($pid != null) {
        $uid = $id . " " . $aid . " " . $oid . " " . $pid;
        setcookie($dom, $uid, time() + 60 * 60 * 24 * 120);
    }
    
}


// Function which sends Tracking Data to Studio for Page Visit
function recognize_cookie($dom)
{
    
    $pieces = explode(" ", $_COOKIE[$dom]);
    
    $id  = $pieces[0];
    $aid = $pieces[1];
    $oid = $pieces[2];
    $pid = $pieces[3];
    
    $var = "http://studio.afw.mdl.io/api/OutboundApp/AppCallback?serviceTypeId=2019&identifier=" . $id . "&accountId=" . $aid . "&outboundId=" . $oid;
    
    // Setup request to send json via POST.
    $payload = json_encode(array(
        "eventoption" => $_SERVER['HTTP_REFERER'],
        "dedup" => $pid
    ));
    
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

    // close curl resource to free up system resources 
    curl_close($ch);       
}


?>
 

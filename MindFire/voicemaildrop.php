<?php
date_default_timezone_set('America/Chicago');
ini_set("memory_limit", "16M");
$myfile = fopen("ringlessStats.csv", "a");



$mobile="";
$url="";
$callerId="";
$audio="";


if ($_POST) {
foreach ($_POST as $key => $value) {
$mobile=htmlspecialchars($_POST["mobile"]);
$url=htmlspecialchars($_POST["url"]);
$callerId=htmlspecialchars($_POST["callerId"]);
$audio=htmlspecialchars($_POST["audio"]);
fwrite($myfile, $mobile);
fwrite($myfile, $url);
fclose($myfile);
}
voicemail_drop($url,$callerId,$audio,$mobile);
}
else
{
$url = $_GET['url'];
$audio = $_GET['audio'];
$mobile = $_GET['mobile'];
$callerId = $_GET['callerId'];
voicemail_drop($url,$callerId,$audio,$mobile);
fwrite($myfile, $mobile);
fwrite($myfile, $url);
fclose($myfile);
}

function voicemail_drop($url,$callerId,$audio,$mobile)
{  
$c_url = 'https://www.mobile-sphere.com/gateway/vmb.php';
$fields = array(
                        'c_uid' => 'mindfire@slybroadcast.com',
                        'c_password' => 'Mind123',
                        'c_phone' => $mobile,
                        'c_url' => $url,
                        'c_date' => 'now',
                        'c_audio' =>$audio,
                        'mobile_only' => '1',
                        'c_callerID' => $callerId              );

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');



//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $c_url);
  curl_setopt($ch,CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($ch,CURLOPT_POST, count($fields));

   
//execute post
 $output=curl_exec($ch);



//close connection
curl_close($ch);
  }

?>


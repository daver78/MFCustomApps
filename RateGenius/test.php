<?php
date_default_timezone_set('America/Chicago');
ini_set("memory_limit", "16M");




if ($_POST) {
foreach ($_POST as $key => $value) {
$mobile=htmlspecialchars($_POST["mobile"]);
$loanofficerphoneno=htmlspecialchars($_POST["loanofficerphoneno"]);
fwrite($myfile, $mobile);
fwrite($myfile, $loanofficerphoneno);
fclose($myfile);

}
find_user("loanofficerslist.csv",$loanofficerphoneno,$mobile);
}


function find_user($filename, $loanofficerphoneno,$mobile) {
    $f = fopen($filename, "r");
    $result = false;
    while ($row = fgetcsv($f)) {
        if ($row[1] == $loanofficerphoneno) {
            $result = $row[2];
            break;
        }
    }
    fclose($f);
    echo  $result;
$url = 'https://www.mobile-sphere.com/gateway/vmb.php';
$fields = array(
                        'c_uid' => 'mindfire@slybroadcast.com',
                        'c_password' => 'Mind123',
                        'c_phone' => $mobile,
                        'c_url' => $result,
                        'c_date' => 'now',
                        'c_audio' => 'mp3',
                        'mobile_only' => '1',
                        'c_callerID' => $loanofficerphoneno              );

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($ch,CURLOPT_POST, count($fields));

   
//execute post
curl_exec($ch);

//close connection
curl_close($ch);
}
?>


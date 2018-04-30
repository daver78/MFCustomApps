<?php
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('America/Los_Angeles');
header('content-type: text/ecmascript');
$playerid = $_GET['PlayerId'];
$accountid = $_GET['AccountId'];

$servername = "cd.com-ext.com";
$username = "bondadmin";
$password = "auto!23$";
$dbname = "highImpact";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 



$sql = 'SELECT AccountId,PlayerId,CouponID,PrizeIDText,PrizeIDValue,PrizeID,StartDate,ExpDate FROM Paychecks where playerid="'.$playerid.'" and accountid="'.$accountid.'" and ExpDate>Now() order by CouponID';
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
  $data[] = array('data' => $row);   
    }
echo json_encode($data);
} else {

$url = array(
    'result' => "0");

echo str_replace('\/','/',json_encode($url));
    
}
$conn->close();
?>
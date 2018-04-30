<?php

include 'config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $data = json_decode(file_get_contents("php://input"));
  print_r($data);


}


$x=$data->NoOfCoupon;

$servername = "cd.com-ext.com";
$username = "bondadmin";
$password = "auto!23$";
$dbname = "highImpact";

$starttime="00:00:00";
$expiredtime="23:59:59";


$res = explode("/", $data->StartDate);
$StartDate= $res[2]."-".$res[0]."-".$res[1];

$res1 = explode("/", $data->ExpDate);
$ExpDate= $res1[2]."-".$res1[0]."-".$res1[1];

$res2 = explode("/", $data->ListDate);
$ListDate= $res2[2]."-".$res2[0]."-".$res2[1];



// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "INSERT INTO Paychecks (AccountId,PlayerId,CouponID,PrizeIDText,PrizeIDValue,PrizeID,StartDate,ExpDate,ListDate)
VALUES ('$data->AccountId','$data->PlayerId','$data->CouponID','$data->PrizeIDText','$data->PrizeIDValue','$data->PrizeID','$StartDate $starttime','$ExpDate $expiredtime','$ListDate')";
echo $sql;
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();



?>
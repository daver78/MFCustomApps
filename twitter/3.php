<?php
session_start();
require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

    define('CONSUMER_KEY',"vlwbbi8IcbKBMHH0qkKlhQYyx");
    define('CONSUMER_SECRET',"ytiesqUo3QJuBC1p3N6siHs3Tnh6sAhPzXixORuWoSrPzjwXF4");
$myfile = fopen("Stats.csv", "a");

if ($_POST) {    

$Message=htmlspecialchars($_POST["message"]);
$access=htmlspecialchars($_POST["access_token"]);
$secret=htmlspecialchars($_POST["access_token_secret"]);
fwrite($myfile, $Message);
fwrite($myfile, $access);
fwrite($myfile, $secret);
fclose($myfile);

$twitter = new TwitterOAuth(
   CONSUMER_KEY,
  CONSUMER_SECRET,
    $access,
    $secret
);

$status = $twitter->post(
    "statuses/update", [
        "status" => $Message    ]
);
print_r($status);
echo ('Status is posted to Twitter successfully with #' . $status->id . PHP_EOL);
}


?>
<?php
session_start();
require 'vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

  define('CONSUMER_KEY',"ZylUrxGxodhW7BVlgbmKamITK");
    define('CONSUMER_SECRET',"V9kndIgQY8CIJIZVLTk33Sw0DUHhWWjs0QHI6VHYEaB1nN6Rfo");

	
	
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

echo ('Status is posted to Twitter successfully with #' . $status->id . PHP_EOL);
}


?>
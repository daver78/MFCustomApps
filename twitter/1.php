<?php

session_start();
require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

    define('CONSUMER_KEY',"vlwbbi8IcbKBMHH0qkKlhQYyx");
    define('CONSUMER_SECRET',"ytiesqUo3QJuBC1p3N6siHs3Tnh6sAhPzXixORuWoSrPzjwXF4");
    define('OAUTH_CALLBACK',"http://ec2-13-56-79-24.us-west-1.compute.amazonaws.com:8084/twitter/2.php");

// create TwitterOAuth object
$twitteroauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
 
// request token of application
$request_token = $twitteroauth->oauth(
    'oauth/request_token', [
        'oauth_callback' => OAUTH_CALLBACK    ]
);
 
// throw exception if something gone wrong
if($twitteroauth->getLastHttpCode() != 200) {
    throw new \Exception('There was a problem performing this request');
}
 
// save token of application to session
$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
 
// generate the URL to make request to authorize our application
$url = $twitteroauth->url(
    'oauth/authorize', [
        'oauth_token' => $request_token['oauth_token']
    ]
);
 
// and redirect
header('Location: '. $url);

?>
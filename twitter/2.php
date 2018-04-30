<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body> 

<?php



session_start();
require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

  define('CONSUMER_KEY',"vlwbbi8IcbKBMHH0qkKlhQYyx");
    define('CONSUMER_SECRET',"ytiesqUo3QJuBC1p3N6siHs3Tnh6sAhPzXixORuWoSrPzjwXF4");
    define('OAUTH_CALLBACK',"http://ec2-13-56-79-24.us-west-1.compute.amazonaws.com:8084/twitter/2.php");

$oauth_verifier = filter_input(INPUT_GET, 'oauth_verifier');
 
if (empty($oauth_verifier) ||
    empty($_SESSION['oauth_token']) ||
    empty($_SESSION['oauth_token_secret'])
) {
    // something's missing, go and login again
    header('Location: ' . 'http://ec2-13-56-79-24.us-west-1.compute.amazonaws.com:8084/twitter/1.php');
}

$connection = new TwitterOAuth(
    CONSUMER_KEY,
    CONSUMER_SECRET,
    $_SESSION['oauth_token'],
    $_SESSION['oauth_token_secret']
);
 
// request user token
$token = $connection->oauth(
    'oauth/access_token', [
        'oauth_verifier' => $oauth_verifier
    ]
);

$twitter = new TwitterOAuth(
   CONSUMER_KEY,
    CONSUMER_SECRET,
    $token['oauth_token'],
    $token['oauth_token_secret']
);



?>

<form method="post" action="http://ec2-13-56-79-24.us-west-1.compute.amazonaws.com:8084/twitter/3.php">  
  Message to Post : <input type="text" name="message">
<input type="hidden" name="access_token" value="<?php echo $token['oauth_token'];?>">
<input type="hidden" name="access_token_secret" value="<?php echo $token['oauth_token_secret'];?>">
  

 <input type="submit" name="submit" value="Submit">  
</form>



</body>
</html>
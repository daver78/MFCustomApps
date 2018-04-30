<?php
	require "main.php";
	 
	if (isset($_SESSION['token'])) {
	  try {
	 	
          ?>

    <form method = "post" action ="status.php">
        <input type = "text" name = "status" placeholder = "Status">
        <input type = "submit">
    </form>

    <form method = "post" action ="link.php">
        <input type = "text" name = "link" placeholder = "Link">
        <input type = "text" name = "message" placeholder = "Message">
        <input type = "submit">
    </form>
    
    <form method = "post" action ="photo.php">
        <input type = "text" name = "photo" placeholder = "Photo Link">
        <input type = "text" name = "message" placeholder = "Caption">
        <input type = "submit">
    </form>

            <?php
          
	  } catch( Facebook\Exceptions\FacebookSDKException $e ) {
	    echo $e->getMessage();
	    exit;
	  }
	}
	else{
		$helper = $fb->getRedirectLoginHelper();
		$permissions = ['email', 'user_posts', 'publish_actions'];
		$callback    = 'http://ec2-13-56-79-24.us-west-1.compute.amazonaws.com:8084/facebook/facebook/app.php';
		$loginUrl    = $helper->getLoginUrl($callback, $permissions);
		echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
	}
?>
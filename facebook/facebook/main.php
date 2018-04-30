<?php
	require "fbsdk/src/Facebook/autoload.php";

	session_start();
	$fb = new Facebook\Facebook([
	  'app_id'                => '1487284684923166',
	  'app_secret'            => 'd65288335505158d17a078aa425fefa9',
	  'default_graph_version' => 'v2.5',
	]);
?>
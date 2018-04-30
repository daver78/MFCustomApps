<?php
    require "main.php";

    if(isset($_SESSION['token'])){
        $data = array(message => $_POST['message'], link => $_POST['link']);
        $res = $fb->post('/me/feed', $data, $_SESSION['token']);
    }
?>
<?php
    require "main.php";

    if(isset($_SESSION['token'])){
        $data = array(message => $_POST['status']);
        $res = $fb->post('/me/feed', $data, $_SESSION['token']);
    }
?>
<?php
    require "main.php";

    if(isset($_SESSION['token'])){
        $data = array(
            message => $_POST['message'],
            source => $fb->fileToUpload($_POST['photo'])    
        );
        $res = $fb->post('/me/photos', $data, $_SESSION['token']);
    }
?>
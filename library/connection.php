<?php

    $location = "localhost";
    $account = "root";
    $password = "345789123";

    if(isset($location) && isset($account) && isset($password)) {
        $link = mysqli_connect($location, $account, $password);
        if(!$link) {
            echo "無法連接資料庫";
            exit();
        }else {
        }
    }

?>
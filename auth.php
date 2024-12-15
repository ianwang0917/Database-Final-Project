<?php
    include("connection.php");
    $ssn = $_GET["ssn"];
    $password = $_GET["password"];

    $select_db = @mysqli_select_db($link, "competition");
    if (!$select_db) {
        echo "<br>找不到資料庫!<br>";
    }
    $sql_query = "SELECT * FROM `user` WHERE `ssn` = '$ssn' AND `password` = '$password';";
    $result = mysqli_query($link, $sql_query);

    $table = ['admin', 'student', 'teacher', 'judge'];

    if(mysqli_num_rows($result)) {

        session_start();
        $_SESSION["ssn"] = $ssn;

        for($i = 0; $i < count($table); $i++) {
            $sql_query2 = "SELECT * FROM $table[$i] WHERE `ssn` = '$ssn'";
            $result2 = mysqli_query($link, $sql_query2);
            if(mysqli_num_rows($result2)) {
                $str = "Location: " . $table[$i] . ".php";
                header($str);
                exit();
                break;
            }
        }
    }
    else {
        header('Location: login.php?wrong=1');
    }

    mysqli_close($link);
?>
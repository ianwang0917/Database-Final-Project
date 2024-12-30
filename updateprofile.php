<?php
    session_start();
    if(!isset($_SESSION["ssn"])){
        header("Location: index.php");
        exit();
    }
    include("connection.php");
    $select_db = @mysqli_select_db($link, "competition");
    if (!$select_db) {
        echo "<br>找不到資料庫!<br>";
        exit();
    }
    $ssn = $_SESSION["ssn"];
    $identity = $_SESSION["identity"];
    if($identity == "student") {
        $sql1 = "UPDATE `user` SET `name` = '$_POST[name]', `email` = '$_POST[email]', `phonenumber` = '$_POST[phonenumber]', `address` = '$_POST[address]' WHERE `ssn` = '$ssn'";
        $result1 = mysqli_query($link, $sql1);

        if($result1) {
            if(mysqli_affected_rows($link) > 0) { // 有成功修改 (行數有影響)
                $sql2 = "UPDATE `student` SET `department` = '$_POST[department]', `grade` = '$_POST[grade]', `sid` = '$_POST[sid]' WHERE `ssn` = '$ssn'";
                $result2 = mysqli_query($link, $sql2);
                if($result2) {
                    if(mysqli_affected_rows($link) > 0) { // 有成功修改 (行數有影響)
                        echo "<script>alert('資料修改成功！'); window.history.back();</script>";
                    }
                    else { // 失敗
                        echo "<script>alert('資料未修改，請聯繫管理員！'); window.history.back();</script>";
                    }
                }
                else {
                    echo "<script>alert('資料修改失敗，請稍後再試！'); window.history.back();</script>";
                }
            }
            else { // 失敗
                echo "<script>alert('資料未修改，可能是資料與舊資料相同！'); window.history.back();</script>";
            }
        }
        else {
            echo "<script>alert('資料修改失敗，請稍後再試！'); window.history.back();</script>";
        }
    } else if($identity == "teacher") {

    } else if($identity == "judge") {

    }
?>
<?php
    include("connection.php");
    $select_db = @mysqli_select_db($link, "competition");

    if(!$select_db) {
        echo "<br>找不到資料庫!<br>";
        exit();
    }

    if (!isset($_POST['identity'])) {
        echo "<script>alert('表單來源無法識別！請重新嘗試'); window.history.back();</script>";
        exit();
    }

    $identity = $_POST["identity"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $phonenumber = $_POST["phone"];
    $address = $_POST["address"];
    $ssn = $_POST["ssn"];

    // 先檢查有沒有這個帳號
    $sql_query = "SELECT `ssn` FROM `user` WHERE `ssn` = '$ssn'";
    $result = mysqli_query($link, $sql_query);
    if(mysqli_num_rows($result)) {
        echo "<script>alert('帳號(身分證字號)已存在，請重新檢查！'); window.history.back();</script>";
        exit();
    }
    
    $sql_query = "INSERT INTO `user` (`ssn`, `name`, `password`, `phonenumber`, `address`, `email`)
                VALUES ('$ssn', '$name', '$password', '$phonenumber', '$address', '$email')";
    $result = mysqli_query($link, $sql_query);
    if(!$result) {
        echo "<script>alert('帳號創進失敗！請稍後再試'); window.history.back();</script>";
        exit();
    }
    else {
        if($identity == "student") { // 學生
            $department = $_POST["department"];
            $grade = $_POST["grade"];
            $sid = $_POST["sid"];

            $sql_query2 = "INSERT INTO `student` (`ssn`, `department`, `grade`, `sid`) VALUES ('$ssn', '$department', '$grade', '$sid')";
            $result2 = mysqli_query($link, $sql_query2);
            if (!$result2) {
                echo "<script>alert('帳號創進失敗！請稍後再試'); window.history.back();</script>";
                exit();
            }
            else {
                echo "<script>alert('帳號註冊成功！請登入'); window.location.href = 'login.php';</script>";
                exit();
            }
        }
        else if($identity == "admin") { // 管理員
            $sql_query2 = "INSERT INTO `admin` (`ssn`, `job`) VALUES ('$ssn', '')";
            $result2 = mysqli_query($link, $sql_query2);
            if (!$result2) {
                echo "<script>alert('帳號創進失敗！請稍後再試'); window.history.back();</script>";
                exit();
            }
            else {
                echo "<script>alert('帳號註冊成功！請登入'); window.location.href = 'login.php';</script>";
                exit();
            }
        }
        else if($identity == "teacher") { // 指導老師
            $degree = $_POST["degree"];

            $sql_query2 = "INSERT INTO `admin` (`ssn`, `degree`) VALUES ('$ssn', '$degree')";
            $result2 = mysqli_query($link, $sql_query2);
            if (!$result2) {
                echo "<script>alert('帳號創進失敗！請稍後再試'); window.history.back();</script>";
                exit();
            }
            else {
                echo "<script>alert('帳號註冊成功！請登入'); window.location.href = 'login.php';</script>";
                exit();
            }
        }
        else if($identity == "judge") { // 評審
            $title = $_POST["title"];

            $sql_query2 = "INSERT INTO `judge` (`ssn`, `title`) VALUES ('$ssn', '$title')";
            $result2 = mysqli_query($link, $sql_query2);
            if (!$result2) {
                echo "<script>alert('帳號創進失敗！請稍後再試'); window.history.back();</script>";
                exit();
            }
            else {
                echo "<script>alert('帳號註冊成功！請登入'); window.location.href = 'login.php';</script>";
                exit();
            }
        }
    }

?>
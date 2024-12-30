<?php
    function handleError ($message) {
        echo "<script>alert('$message'); window.history.back();</script>";
        exit();
    }

    require_once("../library/connection.php");
    $select_db = @mysqli_select_db($link, "competition");

    if(!isset($_POST['identity'])) {
        handleError('表單來源無法識別！請重新嘗試');
    }

    $identity = $_POST["identity"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $phonenumber = $_POST["phone"];
    $address = $_POST["address"];
    $ssn = $_POST["ssn"];

    try {
        if(!mysqli_select_db($link, "competition")) {
            throw new Exception("找不到資料庫！");
        }

        mysqli_begin_transaction($link);

        $sql = "SELECT `ssn` FROM `user` WHERE `ssn` = '$ssn'";
        $result = mysqli_query($link, $sql);
        if(mysqli_num_rows($result) > 0) {
            throw new Exception("帳號(身分證字號)已存在，請重新檢查！");
        }

        $sql = "INSERT INTO `user` (`ssn`, `name`, `password`, `phonenumber`, `address`, `email`)
                VALUES ('$ssn', '$name', '$password', '$phonenumber', '$address', '$email');";
        $result = mysqli_query($link, $sql);
        if($result === false) {
            throw new Exception("帳號創建失敗！請稍後再試");
        }

        if($identity == "student") {
            $department = $_POST["department"];
            $grade = $_POST["grade"];
            $sid = $_POST["sid"];
            $sql = "INSERT INTO `student` (`ssn`, `department`,`grade`, `sid`) 
                    VALUES ('$ssn', '$department', '$grade', '$sid');";
            $result = mysqli_query($link, $sql);
            if($result == false) {
                throw new Exception("學生帳號創建失敗！請稍後再試");
            }
        } else if($identity == "teacher") {
            $degree = $_POST["degree"];
            $sql = "INSERT INTO `teacher` (`ssn`, `degree`) 
                    VALUES ('$ssn', '$degree');";
            $result = mysqli_query($link, $sql);
            if($result == false) {
                throw new Exception("指導老師帳號創建失敗！請稍後再試");
            }
        } else if($identity == "judge") {
            $title = $_POST["title"];
            $sql = "INSERT INTO `judge` (`ssn`, `title`) 
                    VALUES ('$ssn', '$title');";
            $result = mysqli_query($link, $sql);
            if($result == false) {
                throw new Exception("評審帳號創建失敗！請稍後再試");
            }
        } else if ($identity == "admin") {
            $sql = "INSERT INTO `admin` (`ssn`) 
                    VALUES ('$ssn');";
            $result = mysqli_query($link, $sql);
            if($result == false) {
                throw new Exception("管理員帳號創建失敗！請稍後再試");
            }
        } else {
            throw new Exception("無效的身份類型！");
        }

        mysqli_commit($link);
        echo "<script>alert('帳號註冊成功！請登入'); window.location.href = 'login.php';</script>";
    } catch(Exception $e) {
        mysqli_rollback($link);
        handleError($e->getMessage());
    } finally {
        mysqli_close($link);
    }
?>
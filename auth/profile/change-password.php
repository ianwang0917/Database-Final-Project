<?php
    require_once("../../library/connection.php");
    $currentPassword = $_POST["current-password"];
    $newPassword = $_POST["new-password"];

    session_start();
    $ssn = $_SESSION["ssn"];

    $select_db = @mysqli_select_db($link, "competition");
    if(!$select_db) {
        echo "<br>找不到資料庫!<br>";
        exit();
    }

    $sql_query = "SELECT `password` FROM `user` WHERE `ssn` = '$ssn' AND `password` = '$currentPassword';";
    $result = mysqli_query($link, $sql_query);
    if(mysqli_num_rows($result)){
        $sql_query = "UPDATE user SET password = '$newPassword' WHERE ssn = '$ssn'";
        $result2 = mysqli_query($link, $sql_query);

        if($result) {
            if(mysqli_affected_rows($link) > 0) { // 有成功改密碼 (行數有影響)
                session_destroy();
                mysqli_close($link);
                echo "<script>alert('密碼修改成功！請重新登入'); window.location.href = '../../view/index.php';</script>";
                exit();
            }
            else { // 失敗
                echo "<script>alert('密碼未修改，可能是新密碼與舊密碼相同！'); window.history.back();</script>";
            }
        }
        else {
            echo "<script>alert('密碼修改失敗，請稍後再試！'); window.history.back();</script>";
        }
    }
    else {
        echo "<script>alert('當前密碼錯誤！'); window.history.back();</script>";
        mysqli_close($link);
        exit();
    }

    mysqli_close($link);
?>
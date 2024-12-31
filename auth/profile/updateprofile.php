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
    mysqli_begin_transaction($link);

    if($identity == "student") {
        try {
            $sql1 = "UPDATE `user` SET `name` = '$_POST[name]', `email` = '$_POST[email]', `phonenumber` = '$_POST[phonenumber]', `address` = '$_POST[address]' WHERE `ssn` = '$ssn'";
            $result1 = mysqli_query($link, $sql1);

            if (!$result1 || mysqli_affected_rows($link) === -1) {
                throw new Exception("更新使用者資料失敗！請稍後再試或連絡管理員");
            }

            $sql2 = "UPDATE student SET department = '$_POST[department]', grade = '$_POST[grade]', sid = '$_POST[sid]' WHERE ssn = '$ssn'";
            $result2 = mysqli_query($link, $sql2);

            if (!$result2 || mysqli_affected_rows($link) === -1) {
                throw new Exception("更新學生資料失敗！請稍後再試或連絡管理員");
            }

            mysqli_commit($link);
            echo "<script>alert('資料修改成功！'); window.location.href = 'student.php';</script>";
        } catch (Exception $e) {
            mysqli_rollback($link);
            echo "Error: " . $e->getMessage();
            exit();
        } finally {
            mysqli_close($link);
        }
    } else if($identity == "teacher") {
        try {
            $sql1 = "UPDATE `user` SET `name` = '$_POST[name]', `email` = '$_POST[email]', `phonenumber` = '$_POST[phonenumber]', `address` = '$_POST[address]' WHERE `ssn` = '$ssn'";
            $result1 = mysqli_query($link, $sql1);

            if (!$result1 || mysqli_affected_rows($link) === -1) {
                throw new Exception("更新使用者資料失敗！請稍後再試或連絡管理員");
            }

            $sql2 = "UPDATE `teacher` SET `degree` = '$_POST[degree]' WHERE ssn = '$ssn'";
            $result2 = mysqli_query($link, $sql2);

            if (!$result2 || mysqli_affected_rows($link) === -1) {
                throw new Exception("更新指導老師資料失敗！請稍後再試或連絡管理員");
            }

            mysqli_commit($link);
            echo "<script>alert('資料修改成功！'); window.location.href = 'student.php';</script>";
        } catch (Exception $e) {
            mysqli_rollback($link);
            echo "Error: " . $e->getMessage();
            exit();
        } finally {
            mysqli_close($link);
        }
    } else if($identity == "judge") {
        try {
            $sql1 = "UPDATE `user` SET `name` = '$_POST[name]', `email` = '$_POST[email]', `phonenumber` = '$_POST[phonenumber]', `address` = '$_POST[address]' WHERE `ssn` = '$ssn'";
            $result1 = mysqli_query($link, $sql1);

            if (!$result1 || mysqli_affected_rows($link) === -1) {
                throw new Exception("更新使用者資料失敗！請稍後再試或連絡管理員");
            }

            $sql2 = "UPDATE `judge` SET `title` = '$_POST[title]' WHERE ssn = '$ssn'";
            $result2 = mysqli_query($link, $sql2);

            if (!$result2 || mysqli_affected_rows($link) === -1) {
                throw new Exception("更新評審資料失敗！請稍後再試或連絡管理員");
            }

            mysqli_commit($link);
            echo "<script>alert('資料修改成功！'); window.location.href = 'student.php';</script>";
        } catch (Exception $e) {
            mysqli_rollback($link);
            echo "Error: " . $e->getMessage();
            exit();
        } finally {
            mysqli_close($link);
        }
    }

    
?>
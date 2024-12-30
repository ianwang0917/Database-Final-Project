<?php
    $thisyear = 2024;

    require_once("../../library/connection.php");
    $select_db = @mysqli_select_db($link, "competition");
    if (!$select_db) {
        echo "<br>找不到資料庫!<br>";
        exit();
    }

    // 檢查有沒有填自己(避免亂填)，並且將POST的內容儲進php陣列內
    session_start();
    $flag = false; // 假設沒填自己
    $ssn = $_SESSION["ssn"];
    $name = $_POST["name"];
    $student = []; // 1->0, 2->1, 3->2, ...。因此使用陣列時要用0~5
    for($i = 1; $i <= 6; $i++) {
        $key = "student{$i}_id";
        if(isset($_POST[$key]) && !empty($_POST[$key])) {
            $student[] = $_POST[$key];
        }
        if($ssn == $_POST[$key]) {
            $flag = true; // 有填自己
        }
    }
    if(!$flag) {
        echo "<script>alert('你沒有填自己！'); window.history.back();</script>";
        exit();
    }

    $tssn = $_POST["professor_id"];
    // 先查詢隊伍名是否重複
    $sql_query = "SELECT * FROM `team` WHERE `name` = '$name'";
    $result = mysqli_query($link, $sql_query);
    if(mysqli_num_rows($result)) {
        echo "<script>alert('隊伍名稱已經存在！請選擇其他隊名'); window.history.back();</script>";
        exit();
    }

    // 開始確認每個student有沒有組別
    for($i = 0; $i < count($student); $i++) {
        $sql_query = "SELECT * FROM `student` WHERE (`ssn` = '$student[$i]' AND `tid` IS NULL)";
        $result = mysqli_query($link, $sql_query);
        if(!mysqli_num_rows($result)) { // 沒找到沒組的 -> 有組or沒這個人
            $t = $i + 1; // 用來校正成原本的學生1, 2, 3, ...
            echo "<script>alert('學生{$t}已有隊伍或是輸入錯誤！'); window.history.back();</script>";
            exit();
        }
    }

    // 查詢指導老師是否已經指導
    $sql_query = "SELECT * FROM `team` WHERE `teacher_ssn` = '$tssn'";
    $result = mysqli_query($link, $sql_query);
    if(mysqli_num_rows($result)) {
        echo "<script>alert('指導老師已經指導了其他隊伍！'); window.history.back();</script>";
        exit();
    }

    // 最後查詢指導老師是否存在
    $sql_query = "SELECT * FROM `teacher` WHERE `ssn` = '$tssn'";
    $result = mysqli_query($link, $sql_query);
    if(!mysqli_num_rows($result)) {
        echo "<script>alert('指導老師帳號不存在！'); window.history.back();</script>";
        exit();
    }

    // 開始insert into sql
    // 先創立組別
    $sql_query = "INSERT INTO `team` (`name`, `teacher_ssn`, `year`) VALUES ('$name', '$tssn', '$thisyear')";
    $result = mysqli_query($link, $sql_query);
    if (!$result) {
        echo "<script>alert('隊伍創進失敗！請稍後再試'); window.history.back();</script>";
        exit();
    }
    else {
        // 用tssn找剛剛的tid (auto increment)
        $sql_query2 = "SELECT `tid` FROM `team` WHERE `teacher_ssn` = '$tssn'";
        $result2 = mysqli_query($link, $sql_query2);
        $row = mysqli_fetch_assoc($result2);
        $tid = $row["tid"];
        // 開始更新student內的每個成員的tid 
        for($i = 0; $i < count($student); $i++) {
            $sql_query3 = "UPDATE `student` SET `tid` = '$tid' WHERE `ssn` = '$student[$i]'";
            $result3 = mysqli_query($link, $sql_query3);
        }
        echo "<script>alert('成功建立隊伍！'); window.location.href = '../../view/console.php';</script>";
    }
?>
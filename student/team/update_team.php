<?php
require_once("../../library/connection.php");
$select_db = @mysqli_select_db($link, "competition");
if (!$select_db) {
    echo "<br>找不到資料庫!<br>";
    exit();
}

session_start();
if (!isset($_SESSION["ssn"])) {
    header("Location: ../login.php");
    exit();
}

$ssn = $_SESSION["ssn"];
$team_name = $_POST["name"];
$teacher_ssn = $_POST["professor_id"];
$tid = $_POST["tid"]; 

// 驗證資料庫中學生的身分證字號和隊伍的合法性
for ($i = 1; $i <= 6; $i++) {
    $student_key = "student{$i}_id";
    if (isset($_POST[$student_key]) && !empty($_POST[$student_key])) {
        // 只收集非空的學生SSN
        $students[] = $_POST[$student_key];
    }
}

// 驗證指導老師
$sql_teacher = "SELECT * FROM `teacher` WHERE `ssn` = '$teacher_ssn'";
$result_teacher = mysqli_query($link, $sql_teacher);
if (!mysqli_num_rows($result_teacher)) {
    echo "<script>alert('指導老師不存在！'); window.history.back();</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 更新隊伍資料
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $tid = $_POST['tid'];

    $sql_update_team = "UPDATE `team` SET `name` = '$name' WHERE `tid` = '$tid'";
    mysqli_query($link, $sql_update_team);

    // 更新學生資料
    for ($i = 1; $i <= 6; $i++) {
        $student_ssn = $_POST["student{$i}_id"];
        $delete_flag = isset($_POST["delete_student{$i}"]) ? $_POST["delete_student{$i}"] : 0;
        
        // 如果選擇刪除該學生
        if ($delete_flag == 1 && !empty($student_ssn)) {
            // 刪除該學生，將其 `tid` 設為 NULL
            $sql_delete_student = "UPDATE `student` SET `tid` = NULL WHERE `ssn` = '$student_ssn'";
            mysqli_query($link, $sql_delete_student);
        }
        // 如果該欄位有填寫身分證字號且沒有選擇刪除
        elseif (!empty($student_ssn) && $delete_flag == 0) {
            // 更新學生的隊伍ID
            $sql_update_student = "UPDATE `student` SET `tid` = '$tid' WHERE `ssn` = '$student_ssn'";
            mysqli_query($link, $sql_update_student);
        }
    }

    // 更新指導老師資料
    $professor_id = $_POST['professor_id'];
    $sql_update_teacher = "UPDATE `team` SET `teacher_ssn` = '$professor_id' WHERE `tid` = '$tid'";
    mysqli_query($link, $sql_update_teacher);

    echo "<script>alert('隊伍資料更新成功！'); window.location.href = 'team.php';</script>";
}

?>

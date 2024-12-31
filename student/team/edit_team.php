<?php
require_once("../../library/connection.php");
session_start();
if (!isset($_SESSION["ssn"])) {
    header("Location: ../login.php");
    exit();
}

$ssn = $_SESSION["ssn"];
$table = ['admin', 'student', 'teacher', 'judge'];
$select_db = @mysqli_select_db($link, "competition");

if (!$select_db) {
    echo "<br>找不到資料庫!<br>";
    exit();
}

// 查詢隊伍資訊
$sql = "SELECT * FROM `team` WHERE `tid` IN (SELECT `tid` FROM `student` WHERE `ssn` = '$ssn')";
$result = mysqli_query($link, $sql);
$team = mysqli_fetch_assoc($result);

if (!$team) {
    echo "<script>alert('未找到您的隊伍資料！'); window.location.href = '../../team/team.php';</script>";
    exit();
}

// 查詢學生資料
$sql_students = "SELECT * FROM `student` WHERE `tid` = '{$team['tid']}'";
$result_students = mysqli_query($link, $sql_students);
$students = [];
while ($row = mysqli_fetch_assoc($result_students)) {
    $students[] = $row;
}

// 查詢指導老師資料
$sql_teacher = "SELECT `ssn` FROM `teacher` WHERE `ssn` = '{$team['teacher_ssn']}'";
$result_teacher = mysqli_query($link, $sql_teacher);
$teacher = mysqli_fetch_assoc($result_teacher);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>隊伍資料修改</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
        }
        .navbar .site-name {
            font-size: 24px;
            font-weight: bold;
        }
        .navbar .auth-links a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
            font-size: 16px;
        }
        .navbar .auth-links a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 900px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group input:focus {
            border-color: #007BFF;
            outline: none;
        }
        .form-footer {
            text-align: center;
            margin-top: 20px;
        }
        .form-footer a {
            text-decoration: none;
            color: #007BFF;
        }
        .form-footer a:hover {
            text-decoration: underline;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            background-color: #007BFF;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            font-size: 14px;
        }
		.action-buttons {
			display: inline-block;
			margin-left: 10px;
		}
		.action-buttons label {
			display: inline-flex;
			align-items: center;
			margin-right: 15px; 
		}

		.action-buttons input[type="checkbox"] {
			margin-right: 5px; 
		}

    </style>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const form = document.getElementById("team-form");
            const ssnPattern = /^[A-Z]\d{9}$/; 
            const errorMessage = document.getElementById("error-message");

            form.addEventListener("submit", (e) => {
                const inputs = document.querySelectorAll("input");
                let valid = true;

                inputs.forEach(input => {
                    if (input.value.trim() === "") {
                        valid = false;
                        errorMessage.textContent = "所有*必填欄位皆不可為空！";
                    } else if (input.id.includes("id") && !ssnPattern.test(input.value)) {
                        valid = false;
                        errorMessage.textContent = `${input.previousElementSibling.textContent} 格式錯誤！`;
                    }
                });

                if (!valid) {
                    e.preventDefault();
                }
            });
        });
    </script>
</head>
<body>
    <div class="navbar">
        <div class="site-name">高雄大學學生創意競賽</div>
        <div class="auth-links">
            <a href="../../auth/logout.php" id="logout">登出</a>
        </div>
    </div>
    <div class="container">
        <h1>隊伍資料修改</h1>
        <form action="update_team.php" method="POST">
    <div class="form-group">
        <label for="name">隊伍名稱<span>*</span></label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($team['name']); ?>" required>
        <input type="hidden" name="tid" value="<?php echo htmlspecialchars($team['tid']); ?>">
    </div>
    
    <?php for ($i = 1; $i <= 6; $i++): ?>
        <div class="form-group">
            <label for="student<?php echo $i; ?>-id">
                學生<?php echo $i; ?>的身分證字號
            </label>
            <input type="text" 
                   id="student<?php echo $i; ?>-id" 
                   name="student<?php echo $i; ?>_id" 
                   value="<?php echo isset($students[$i - 1]) ? htmlspecialchars($students[$i - 1]['ssn']) : ''; ?>">

            <div class="action-buttons">
                <!-- 顯示刪除選項 -->
                <?php if (isset($students[$i - 1])): ?>
                    <label for="delete_student<?php echo $i; ?>">
                        刪除<input type="checkbox" id="delete_student<?php echo $i; ?>" name="delete_student<?php echo $i; ?>" value="1">  
                    </label>
                <?php else: ?>
                    <label for="add_student<?php echo $i; ?>">
                         新增<input type="checkbox" id="add_student<?php echo $i; ?>" name="add_student<?php echo $i; ?>" value="1">
                    </label>
                <?php endif; ?>
            </div>
        </div>
    <?php endfor; ?>

    <div class="form-group">
        <label for="professor-id">指導老師的身分證字號</label>
        <input type="text" 
               id="professor-id" 
               name="professor_id" 
               value="<?php echo htmlspecialchars($teacher['ssn'] ?? ''); ?>">
    </div>

    <button class="btn" type="submit">確認修改隊伍</button>
</form>


        <div class="form-footer">
            <a href="../../student/team/team.php">回到隊伍資訊</a>
        </div>
    </div>
</body>
</html>

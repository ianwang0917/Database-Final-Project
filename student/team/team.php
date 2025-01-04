<?php
session_start();
if (!isset($_SESSION["ssn"])) {
    header("Location: index.php");
    exit();
}

require_once("../../library/connection.php");
$select_db = @mysqli_select_db($link, "competition");
if (!$select_db) {
    echo "<br>找不到資料庫!<br>";
    exit();
}

$ssn = $_SESSION["ssn"];
$identity = $_SESSION["identity"]; // 確認是學生

// 獲取隊伍 ID
$team_id = null;
if ($identity === "student") {
    // 學生角色：從 student 表中查找隊伍 ID
    $query_team_id = "SELECT tid FROM student WHERE ssn = '$ssn'";
    $result_team_id = mysqli_query($link, $query_team_id);
    if (!$result_team_id) {
        echo "查詢學生的隊伍 ID 失敗: " . mysqli_error($link);
        exit();
    }
    $team_id_row = mysqli_fetch_assoc($result_team_id);
    $team_id = $team_id_row["tid"];
}

// 查詢隊伍名稱與指導教授名稱
$sql_team = "
    SELECT team.name AS team_name, user.name AS teacher_name
    FROM team
    JOIN user ON team.teacher_ssn = user.ssn
    WHERE team.tid = '$team_id'
";
$result_team = mysqli_query($link, $sql_team);
if (!$result_team) {
    echo "查詢隊伍資料失敗: " . mysqli_error($link);
    exit();
}
$team_info = mysqli_fetch_assoc($result_team);

// 查詢學生成員
$sql_students = "
    SELECT user.name AS student_name, student.sid
    FROM student
    JOIN user ON student.ssn = user.ssn
    WHERE student.tid = '$team_id'
";
$result_students = mysqli_query($link, $sql_students);
if (!$result_students) {
    echo "查詢學生資料失敗: " . mysqli_error($link);
    exit();
}

// 將學生名稱和 sid 存入陣列
$students = [];
while ($row = mysqli_fetch_assoc($result_students)) {
    $students[] = [
        'name' => $row["student_name"],
        'sid' => $row["sid"]
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #DCDCDC;
        }
         /* Navbar Styling */
         .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #c5a562;
            color: white;
            position: relative;
        }

        .navbar .logo img {
            height: 50px;
            margin-right: 15px;
        }

        .navbar .site-name {
            font-size: 28px;
            color: #101020;
            font-weight: bold;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .navbar .auth-links a {
            color: #101020;
            text-decoration: none;
            margin-left: 15px;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .navbar .auth-links a:hover {
            color: #ffeb3b;
            text-decoration: underline;
        }
        .main-content {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            border-bottom: 2px solid #007BFF;
            padding-bottom: 5px;
        }
        .team-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .team-info div {
            margin-bottom: 10px;
        }
        .team-info label {
            font-weight: bold;
        }
        .buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .buttons button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            background-color: #007BFF;
            transition: background-color 0.3s;
        }
        .buttons button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="../../img/logo.png" alt="Logo">
        </div>
        <div class="site-name">高雄大學學生創意競賽</div>
        <div class="auth-links">
            <a href="../../view/console.php">返回個人資料</a>
        </div>
    </div>
    <div class="main-content">
        <!-- 隊伍資訊區域 -->
		<?php if (!$team_id) {
				echo "未找到相關隊伍資料。";
				exit();}
		?>
        <div class="section">
            <div class="section-title">隊伍資訊</div>
            <div class="team-info">
                <div><label>隊伍名稱：</label> <?php echo htmlspecialchars($team_info["team_name"]); ?></div>
                <div><label>指導教授：</label> <?php echo htmlspecialchars($team_info["teacher_name"]); ?></div>
                <?php for ($i = 0; $i < min(count($students), 6); $i++): ?>
                    <div>
                        <label>學生<?php echo $i + 1; ?>：</label> 
                        <?php 
                            if (isset($students[$i])) {
                                echo htmlspecialchars($students[$i]['name']) . " " . htmlspecialchars($students[$i]['sid']);
                            }
                        ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- 操作功能按鈕區域 -->
        <div class="section">
            <div class="section-title">功能選單</div>
            <div class="buttons">
                <?php if ($identity === "student"): ?>
                    <button onclick="location.href='../../student/team/edit_team.php'">修改隊伍資料</button>
                    <button onclick="location.href='../../student/project_management.html'">上傳作品</button>
                <?php endif; ?>
                <button onclick="location.href='../../view/browse-project.php'">瀏覽作品</button>
            </div>
        </div>
    </div>
</body>
</html>

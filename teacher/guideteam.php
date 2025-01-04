<?php
session_start();
if (!isset($_SESSION["ssn"]) || $_SESSION["identity"] !== "teacher") {
    header("Location: index.php");
    exit();
}

require_once("../library/connection.php");
$select_db = @mysqli_select_db($link, "competition");
if (!$select_db) {
    echo "<br>找不到資料庫!<br>";
    exit();
}

$ssn = $_SESSION["ssn"];

// 查詢教師所有隊伍（包括歷屆）
$sql_teams = "
    SELECT tid, name, year
    FROM team
    WHERE teacher_ssn = '$ssn'
    ORDER BY year DESC, name ASC
";
$result_teams = mysqli_query($link, $sql_teams);
if (!$result_teams) {
    echo "查詢隊伍資料失敗: " . mysqli_error($link);
    exit();
}

// 收集隊伍資料
$teams = [];
while ($row = mysqli_fetch_assoc($result_teams)) {
    $teams[] = $row;
}

// 獲取目前選中的隊伍 ID
$selected_team_id = isset($_POST["selected_team"]) ? $_POST["selected_team"] : (count($teams) > 0 ? $teams[0]["tid"] : null);

// 查詢選中隊伍的詳細資訊
if ($selected_team_id) {
    $sql_team_info = "
        SELECT team.name AS team_name, user.name AS teacher_name
        FROM team
        JOIN user ON team.teacher_ssn = user.ssn
        WHERE team.tid = '$selected_team_id'
    ";
    $result_team_info = mysqli_query($link, $sql_team_info);
    $team_info = $result_team_info ? mysqli_fetch_assoc($result_team_info) : null;

    // 查詢選中隊伍的學生
    $sql_students = "
        SELECT user.name AS student_name, student.sid
        FROM student
        JOIN user ON student.ssn = user.ssn
        WHERE student.tid = '$selected_team_id'
    ";
    $result_students = mysqli_query($link, $sql_students);
    $students = [];
    while ($row = mysqli_fetch_assoc($result_students)) {
        $students[] = $row;
    }

    // 查詢選中隊伍的作品
    $sql_pieces = "
        SELECT name, demo, poster, code, document
        FROM piece
        WHERE tid = '$selected_team_id'
    ";
    $result_pieces = mysqli_query($link, $sql_pieces);
    $pieces = [];
    while ($row = mysqli_fetch_assoc($result_pieces)) {
        $pieces[] = $row;
    }
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
            <img src="../img/logo.png" alt="Logo">
        </div>
        <div class="site-name">高雄大學學生創意競賽</div>
        <div class="auth-links">
            <a href="../view/console.php">返回個人資料</a>
        </div>
    </div>
    <div class="main-content">
		 <?php if (empty($teams)): ?>
            <!-- 教師無隊伍時顯示訊息 -->
            <div class="no-teams">
                您目前尚未指導任何隊伍。
            </div>
		<?php else: ?>
        <!-- 下拉選單讓教師選擇隊伍 -->
        <form method="POST" class="team-select">
            <label for="selected_team">選擇隊伍：</label>
            <select name="selected_team" id="selected_team" onchange="this.form.submit()">
                <?php foreach ($teams as $team): ?>
                    <option value="<?php echo $team["tid"]; ?>" <?php echo $team["tid"] === $selected_team_id ? "selected" : ""; ?>>
                        <?php echo htmlspecialchars($team["name"]) . " - " . $team["year"]; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

			<?php if ($selected_team_id && $team_info): ?>
            <!-- 隊伍資訊區域 -->
            <div class="section">
                <div class="section-title">隊伍資訊</div>
                <div class="team-info">
                    <div><label>隊伍名稱：</label> <?php echo htmlspecialchars($team_info["team_name"]); ?></div>
                    <div><label>指導教授：</label> <?php echo htmlspecialchars($team_info["teacher_name"]); ?></div>
                </div>
            </div>

            <!-- 學生成員資訊 -->
            <div class="section">
                <div class="section-title">學生成員</div>
                <?php foreach ($students as $index => $student): ?>
                    <div><label>學生<?php echo $index + 1; ?>：</label> <?php echo htmlspecialchars($student["student_name"]) . " (" . htmlspecialchars($student["sid"]) . ")"; ?></div>
                <?php endforeach; ?>
            </div>

            <!-- 作品資訊 -->
            <div class="section">
                <div class="section-title">作品資訊</div>
                <?php if (empty($pieces)): ?>
                    <p>該隊伍尚無作品資訊。</p>
                <?php else: ?>
                    <?php foreach ($pieces as $piece): ?>
                        <div>
                            <p><strong>作品名稱：</strong> <?php echo htmlspecialchars($piece["name"]); ?></p>
                            <p><strong>Demo：</strong>
                                <?php echo !empty($piece["demo"]) ? "<a href='" . htmlspecialchars($piece["demo"]) . "' target='_blank'>觀看 Demo</a>" : "無"; ?>
                            </p>
                            <p><strong>海報：</strong>
                                <?php echo !empty($piece["poster"]) ? "<a href='" . htmlspecialchars($piece["poster"]) . "' target='_blank'>下載海報</a>" : "無"; ?>
                            </p>
                            <p><strong>程式碼：</strong>
                                <?php echo !empty($piece["code"]) ? "<a href='" . htmlspecialchars($piece["code"]) . "' target='_blank'>查看程式碼</a>" : "無"; ?>
                            </p>
                            <p><strong>文件：</strong>
                                <?php echo !empty($piece["document"]) ? "<a href='" . htmlspecialchars($piece["document"]) . "' target='_blank'>下載文件</a>" : "無"; ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p>目前無法顯示隊伍資訊。</p>
        <?php endif; ?>
		<?php endif; ?>
    </div>
</body>
</html>
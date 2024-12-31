<?php
require_once("../library/connection.php");

// 建立資料庫連接
$select_db = @mysqli_select_db($link, "competition");
if (!$select_db) {
    echo "<br>找不到資料庫!<br>";
    exit();
}

$insert_success = null;
$error_message = null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["tid"], $_POST["name"], $_POST["demo"], $_POST["poster"], $_POST["document"])) {
    $tid = $link->real_escape_string($_POST["tid"]); // 隊伍編號
    $name = $link->real_escape_string($_POST["name"]); // 作品名稱
    $demo = $link->real_escape_string($_POST["demo"]); // 展示連結
    $poster = $link->real_escape_string($_POST["poster"]); // 海報連結
    $code = isset($_POST["code"]) ? $link->real_escape_string($_POST["code"]) : null; // 程式碼連結 (可選)
    $document = $link->real_escape_string($_POST["document"]); // 文件連結

    // 檢查隊伍編號是否存在於資料庫
    $sql_check_tid = "SELECT * FROM team WHERE tid = '$tid'";
    $result_check_tid = $link->query($sql_check_tid);

    if ($result_check_tid && $result_check_tid->num_rows > 0) {
        //隊伍編號存在
        $sql_insert = "INSERT INTO piece (tid, name, demo, poster, code, document) 
                       VALUES ('$tid', '$name', '$demo', '$poster', " . ($code ? "'$code'" : "NULL") . ", '$document')";

        if ($link->query($sql_insert) === TRUE) {
            $insert_success = true;
        } else {
            $insert_success = false;
            $error_message = "作品新增失敗：" . $link->error;
        }
    } else {
        //隊伍編號不存在        
        $insert_success = false;
        $error_message = "錯誤：找不到隊伍編號 $tid";
    }
}

// 查詢所有作品
$sql_query_projects = "SELECT * FROM piece ORDER BY pid DESC";
$result_projects = $link->query($sql_query_projects);
$projects = [];
if ($result_projects && $result_projects->num_rows > 0) {
    while ($row = $result_projects->fetch_assoc()) {
        $projects[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #007BFF;
            padding: 10px 20px;
            color: white;
            display: flex;
            justify-content: space-between;
        }
        .navbar a {
            color: white;
            text-decoration: none;
        }
        .container {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .project-list {
            margin-top: 30px;
        }
        .project-item {
            background: white;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .project-item h3 {
            margin: 0 0 10px;
        }
        .project-item a {
            color: #007BFF;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div>高雄大學學生創意競賽</div>
        <div>
            <a href="../view/console.php">回到學生頁面</a>
        </div>
    </div>

    <div class="container">
        <h1>新增作品</h1>

        <?php if ($insert_success === true): ?>
            <p style="color: green; text-align: center;">作品新增成功！</p>
        <?php elseif ($insert_success === false): ?>
            <p style="color: red; text-align: center;"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="tid">隊伍編號</label>
                <input type="text" id="tid" name="tid" required>
            </div>
            <div class="form-group">
                <label for="name">作品名稱</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="demo">展示連結 (YouTube 或其他)</label>
                <input type="url" id="demo" name="demo" required>
            </div>
            <div class="form-group">
                <label for="poster">海報連結</label>
                <input type="url" id="poster" name="poster" required>
            </div>
            <div class="form-group">
                <label for="code">程式碼連結 (可選)</label>
                <input type="url" id="code" name="code">
            </div>
            <div class="form-group">
                <label for="document">文件連結 (PDF)</label>
                <input type="url" id="document" name="document" required>
            </div>
            <div class="form-group">
                <button type="submit">新增</button>
            </div>
        </form>

        <div class="project-list">
            <?php foreach ($projects as $project): ?>
                <div class="project-item">
                    <h3><?= htmlspecialchars($project['name']) ?></h3>
                    <p>隊伍編號：<?= htmlspecialchars($project['tid']) ?></p>
                    <p><a href="<?= htmlspecialchars($project['demo']) ?>" target="_blank">展示連結</a></p>
                    <p><a href="<?= htmlspecialchars($project['poster']) ?>" target="_blank">海報連結</a></p>
                    <?php if (!empty($project['code'])): ?>
                        <p><a href="<?= htmlspecialchars($project['code']) ?>" target="_blank">程式碼連結</a></p>
                    <?php endif; ?>
                    <p><a href="<?= htmlspecialchars($project['document']) ?>" target="_blank">文件連結</a></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

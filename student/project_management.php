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

        
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">
            <img src="../img/logo.png" alt="Logo">
        </div>
        <div class="site-name">高雄大學學生創意競賽</div>
        <div class="auth-links">
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
    </div>
</body>
</html>

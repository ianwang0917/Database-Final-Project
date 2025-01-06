<?php
    require_once("../library/connection.php");

    // 建立資料庫連接
    $select_db = @mysqli_select_db($link, "competition");
    if (!$select_db) {
        echo "<br>找不到資料庫!<br>";
        exit();
    }

    // 新增公告處理邏輯
    $insert_success = null;
    $error_message = null;

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["aid"], $_POST["title"], $_POST["content"])) {
        $aid = mysqli_real_escape_string($link, $_POST["aid"]); // 公告編號
        $title = mysqli_real_escape_string($link, $_POST["title"]); // 公告標題
        $content = mysqli_real_escape_string($link, $_POST["content"]); // 公告內容
        $datetime = !empty($_POST["datetime"]) ? mysqli_real_escape_string($link, $_POST["datetime"]) : date("Y-m-d H:i:s");
        $ssn = $_POST["ssn"];

        // 檢查公告編號是否重複
        $check_duplicate_query = "SELECT aid FROM announcement WHERE aid = '$aid'";
        $result_duplicate = mysqli_query($link, $check_duplicate_query);

        if (mysqli_num_rows($result_duplicate) > 0) {
            $insert_success = false;
            $error_message = "公告編號已存在，請使用其他編號！";
        } else {
            // 插入新的公告
            $sql_insert = "INSERT INTO announcement (aid, ssn, title, content, datetime) VALUES ('$aid','$ssn' , '$title', '$content', '$datetime')";
            if (mysqli_query($link, $sql_insert)) {
                $insert_success = true;
            } else {
                $insert_success = false;
                $error_message = "公告新增失敗，請重試！";
            }
        }
    }

    // 從資料庫中查詢所有公告資料
    $sql_query_announcements = "SELECT aid, title, content, datetime FROM announcement ORDER BY datetime DESC";
    $result_announcements = mysqli_query($link, $sql_query_announcements);

    // 檢查查詢結果
    $announcements = [];
    if ($result_announcements && mysqli_num_rows($result_announcements) > 0) {
        while ($row = mysqli_fetch_assoc($result_announcements)) {
            $announcements[] = $row; // 儲存每一筆公告資料
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>高雄大學學生創意競賽</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
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

        .announcement-container,
        .form-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .announcement-header,
        .form-header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        .announcement-list {
            list-style: none;
            padding: 0;
        }

        .announcement-item {
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
            transition: box-shadow 0.3s ease;
        }

        .announcement-item:hover {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .announcement-title {
            font-size: 18px;
            font-weight: bold;
            color: #007BFF;
            margin-bottom: 10px;
        }

        .announcement-date {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }

        .announcement-content {
            font-size: 16px;
            color: #333;
        }

        .footer {
            text-align: center;
            background-color: #333;
            color: white;
            padding: 20px;
            margin-top: 40px;
        }

        .footer a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 16px;
        }

        .footer a:hover {
            color: #ffeb3b;
        }

        /* 新增公告表單樣式 */
        .form-container form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .form-container input,
        .form-container textarea {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-container button {
            padding: 10px;
            font-size: 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .success-message,
        .error-message {
            text-align: center;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .success-message {
            color: green;
        }

        .error-message {
            color: red;
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
        <a href="../view/console.php">返回個人資料</a>
        </div>
    </div>

    <!-- 新增公告表單 -->
    <div class="form-container">
        <div class="form-header">新增公告</div>
        <?php if ($insert_success === true): ?>
            <div class="success-message">公告新增成功！</div>
        <?php elseif ($insert_success === false): ?>
            <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <input type="number" name="aid" placeholder="公告編號" required>
            <input type="ssn" name="ssn" placeholder="公告發布者" required>
            <input type="text" name="title" placeholder="公告標題" required>
            <textarea name="content" rows="5" placeholder="公告內容" required></textarea>
            <label>選擇發布時間：</label>
            <input type="datetime-local" name="datetime">
            <button type="submit">新增公告</button>
        </form>
    </div>

    <!-- Announcement Container -->
    <div class="announcement-container">
        <div class="announcement-header">所有公告</div>
        <ul class="announcement-list">
            <?php if (!empty($announcements)): ?>
                <?php foreach ($announcements as $announcement): ?>
                    <li class="announcement-item">
                        <div class="announcement-title">
                            ID：<?= htmlspecialchars($announcement["aid"]) ?> - <?= htmlspecialchars($announcement["title"]) ?>
                        </div>
                        <div class="announcement-date">
                            發布日期：<?= htmlspecialchars($announcement["datetime"]) ?>
                        </div>
                        <div class="announcement-content">
                            <?= nl2br(htmlspecialchars($announcement["content"])) ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="announcement-item">目前沒有公告。</li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 高雄大學學生創意競賽</p>
        <a href="index.php">首頁</a>
        <a href="#">關於我們</a>
        <a href="#">聯繫方式</a>
    </div>

</body>
</html>
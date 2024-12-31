<?php
    require_once("../library/connection.php");

    session_start();
    if($_SESSION["identity"] != "admin") {
        echo "<script>alert('你無權使用此頁面！');windows.history.back();</script>";
    }

    // 建立資料庫連接
    $select_db = @mysqli_select_db($link, "competition");
    if (!$select_db) {
        echo "<br>找不到資料庫!<br>";
        exit();
    }

    // 處理修改公告的表單提交邏輯
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_announcement"])) {
        $aid = mysqli_real_escape_string($link, $_POST["aid"]);
        $title = mysqli_real_escape_string($link, $_POST["title"]);
        $content = mysqli_real_escape_string($link, $_POST["content"]);
        $datetime = mysqli_real_escape_string($link, $_POST["datetime"]);

        $update_query = "UPDATE announcement 
                        SET title = '$title', content = '$content', datetime = '$datetime' 
                        WHERE aid = '$aid'";

        if (mysqli_query($link, $update_query)) {
            echo "<script>alert('公告已成功更新！');</script>";
        } else {
            echo "<script>alert('公告更新失敗：" . mysqli_error($link) . "');</script>";
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
    <title>View Announcements</title>
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
        .edit-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .announcement-header,
        .edit-header {
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
        }

        .announcement-title {
            font-size: 18px;
            font-weight: bold;
            color: #007BFF;
            margin-bottom: 10px;
        }

        .edit-container form {
            display: flex;
            flex-direction: column;
        }

        .edit-container label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .edit-container input,
        .edit-container textarea {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .edit-container button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .edit-container button:hover {
            background-color: #218838;
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

    <!-- Edit Announcement Section -->
    <div class="edit-container">
        <div class="edit-header">修改公告</div>
        <form method="POST" action="">
            <label for="aid">公告ID：</label>
            <input type="number" id="aid" name="aid" required placeholder="請輸入公告ID">

            <label for="title">標題：</label>
            <input type="text" id="title" name="title" required placeholder="請輸入公告標題">

            <label for="content">內容：</label>
            <textarea id="content" name="content" rows="5" required placeholder="請輸入公告內容"></textarea>

            <label for="datetime">發布日期：</label>
            <input type="datetime-local" id="datetime" name="datetime" required>

            <button type="submit" name="update_announcement">更新公告</button>
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
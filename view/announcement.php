<?php
require_once("../library/connection.php");

// 建立資料庫連接
$select_db = @mysqli_select_db($link, "competition");
if (!$select_db) {
    echo "<br>找不到資料庫!<br>";
    exit();
}

// 從資料庫中查詢所有公告資料
$sql_query_announcements = "SELECT title, content, datetime FROM announcement ORDER BY datetime DESC";
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

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #007BFF;
            color: white;
        }

        .navbar .site-name {
            font-size: 28px;
            font-weight: bold;
        }

        .announcement-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .announcement-header {
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
    </style>

</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <div class="site-name">高雄大學學生創意競賽</div>
    </div>

    <!-- Announcement Container -->
    <div class="announcement-container">
        <div class="announcement-header">所有公告</div>
        <ul class="announcement-list">
            <?php if (!empty($announcements)): ?>
                <?php foreach ($announcements as $announcement): ?>
                    <li class="announcement-item">
                        <div class="announcement-title"><?= htmlspecialchars($announcement["title"]) ?></div>
                        <div class="announcement-date">發布日期：<?= htmlspecialchars($announcement["datetime"]) ?></div>
                        <div class="announcement-content"><?= nl2br(htmlspecialchars($announcement["content"])) ?></div>
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

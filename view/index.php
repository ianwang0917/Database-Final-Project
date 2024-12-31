<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>高雄大學學生創意競賽</title>
    <style>
        /* General styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9fafc;
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

        /* Announcements Section */
        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            flex-direction: column;
        }

        .main-image {
            width: 100%;
            max-width: 800px;
            margin-bottom: 20px;
        }

        .main-image img {
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .announcements {
            width: 100%;
            max-width: 800px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .announcement-header {
            background-color: #c5a562;
            color: #101020 ;
            padding: 15px;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .announcement-header:hover {
            background-color:  #d5c6ac;
        }

        .announcement-list {
            max-height: 400px;
            overflow-y: auto;
            padding: 10px;
        }

        .announcement-item {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        .announcement-item:last-child {
            border-bottom: none;
        }

        .announcement-title {
            font-size: 18px;
            font-weight: bold;
            color: #007BFF;
        }

        .announcement-date {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
        }

        .announcement-description {
            font-size: 16px;
            color: #333;
        }

        /* Footer */
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
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: #ffeb3b;
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar .site-name {
                font-size: 24px;
            }

            .footer p {
                font-size: 12px;
            }
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
            <a href="../auth/login.php">登入</a>
            <a href="../register/register.html">註冊</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="main-image">
            <img src="../img/main.jpg" alt="Main Image">
        </div>
        <div class="announcements">
            <div class="announcement-header" onclick="window.location.href='announcement.php'">最新公告 > 更多</div>
            <div class="announcement-list">
                <?php
                // 資料庫連接
                require_once("../library/connection.php");
                // 建立連接
                $select_db = @mysqli_select_db($link, "competition");

                // 檢查資料庫連接
                if(!$select_db) {
                    echo "<br>找不到資料庫!<br>";
                }

                // 查詢公告資料
                $sql_query = "SELECT `title`, `datetime`, `content` FROM `announcement` ORDER BY `datetime` DESC;";
                $result = mysqli_query($link, $sql_query);

                if ($result -> num_rows > 0) {
                    // 輸出每一筆資料
                    $i = 0;
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="announcement-item">';
                        echo '<div class="announcement-title">' . htmlspecialchars($row["title"]) . '</div>';
                        echo '<div class="announcement-date">' . htmlspecialchars($row["datetime"]) . '</div>';
                        echo '<div class="announcement-description">' . htmlspecialchars($row["content"]) . '</div>';
                        echo '</div>';
                        $i++;
                        if ($i >= 4)
                            break;
                    }
                } else {
                    echo '<div class="announcement-item">目前沒有公告。</div>';
                }

                ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 高雄大學學生創意競賽</p>
        <div>
            <a href="#">首頁</a>
            <a href="#">關於我們</a>
            <a href="#">聯繫方式</a>
        </div>
    </div>

</body>
</html>


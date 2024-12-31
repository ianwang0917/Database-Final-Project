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
            background-color: #f4f4f9;
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
        <div class="site-name">高雄大學學生創意競賽</div>
        <div class="auth-links">
            <a href="index.php">Logout</a>
        </div>
    </div>
    <div class="main-content">
        <!-- 隊伍資訊區域 -->
        <div class="section">
            <div class="section-title">隊伍資訊</div>
            <div class="team-info">
                <div><label>隊伍名稱：</label> </div>
                <div><label>指導教授：</label> </div>
                <div><label>學生1：</label> </div>
                <div><label>學生2：</label> </div>
                <div><label>學生3：</label> </div>
                <div><label>學生4：</label> </div>
            </div>
        </div>

        <!-- 操作功能按鈕區域 -->
        <div class="section">
            <div class="section-title">功能選單</div>
            <div class="buttons">
                <button onclick="location.href='edit_team.html'">修改隊伍資料</button>
                <button onclick="location.href='project_management.html'">上傳作品</button>
                <button onclick="location.href='browse-project.html'">瀏覽作品</button>
            </div>
        </div>
    </div>
</body>
</html>

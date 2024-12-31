<?php
require_once("../library/connection.php");

// 建立資料庫連接
$select_db = @mysqli_select_db($link, "competition");
if (!$select_db) {
    echo "<br>找不到資料庫!<br>";
    exit();
}

// 從資料庫查詢所有作品
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
    <title>歷屆作品瀏覽</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9fafc;
            color: #333;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #c5a562;
            color: white;
            position: relative;
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

        .navbar a {
            color: white;
            text-decoration: none;
        }
        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .project-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .project-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .project-item h3 {
            margin: 0 0 10px;
            color: #007BFF;
        }
        .project-item img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }
        .project-item a {
            color: #007BFF;
            text-decoration: none;
            margin-right: 10px;
        }
        .filter-section {
            margin-bottom: 20px;
            text-align: center;
        }
        .filter-section input {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
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
    
     <div class="container">
        <h1>歷屆學生作品展示</h1>
        
        <div class="filter-section">
            <input type="text" id="searchInput" placeholder="搜尋作品名稱" onkeyup="filterProjects()">
        </div>

        <div id="projectList" class="project-list">
            <?php if (!empty($projects)): ?>
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
            <?php else: ?>
                <p style="text-align:center;">目前尚無作品</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function filterProjects() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const projects = document.getElementsByClassName('project-item');
            
            Array.from(projects).forEach(project => {
                const title = project.querySelector('h3').textContent.toLowerCase();
                project.style.display = title.includes(searchInput) ? 'block' : 'none';
            });
        }
    </script>
</body>
</html>
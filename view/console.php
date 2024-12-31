<?php
    session_start();
    // Validate session and redirect if needed
    $allowed_identities = ["admin", "judge", "student", "teacher"];
    if (!isset($_SESSION["identity"]) || !in_array($_SESSION["identity"], $allowed_identities)) {
        header("Location: login.php");
        exit();
    }
    
    require_once("../library/connection.php");
    $select_db = @mysqli_select_db($link, "competition");
    if(!$select_db) {
        echo "<br>找不到資料庫!<br>";
    }
    
    // Get user data
    $ssn = $_SESSION["ssn"];
    $identity = $_SESSION["identity"];
    
    // Prepare and execute user query with prepared statement
    $sql_query_user = "SELECT * FROM `user` WHERE `ssn` = ?";
    $stmt = mysqli_prepare($link, $sql_query_user);
    mysqli_stmt_bind_param($stmt, "s", $ssn);
    mysqli_stmt_execute($stmt);
    $result_user = mysqli_stmt_get_result($stmt);
    $row_user = mysqli_fetch_assoc($result_user);
    
    // Get role-specific data
    $role_data = [];
    if ($identity != "admin") {
        $sql_query_role = "SELECT * FROM `$identity` WHERE `ssn` = ?";
        $stmt_role = mysqli_prepare($link, $sql_query_role);
        mysqli_stmt_bind_param($stmt_role, "s", $ssn);
        mysqli_stmt_execute($stmt_role);
        $result_role = mysqli_stmt_get_result($stmt_role);
        $role_data = mysqli_fetch_assoc($result_role);
    }
?>

<!DOCTYPE html>
<html lang="zh-Hant-TW">
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
            border-bottom: 2px solid #337db5a1;
            padding-bottom: 5px;
        }
        .personal-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .personal-info div {
            margin-bottom: 10px;
        }
        .personal-info label {
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
        #logout, #home {
            text-decoration: none;
            color: #101020;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="../img/logo.png" alt="Logo">
        </div>
        <div class="site-name">
            <a href="#" id="home">高雄大學學生創意競賽</a>
        </div>
        <div class="auth-links">
            <a href="../auth/logout.php" id="logout">登出</a>
        </div>
    </div>
    <div class="main-content">
        <!-- 個人資料區域 -->
        <div class="section">
            <div class="section-title">
                <?php
                    $titles = [
                        'admin' => '管理員',
                        'judge' => '評審',
                        'student' => '學生',
                        'teacher' => '指導老師'
                    ];
                    echo $titles[$identity] . '個人資料';
                ?>
            </div>
            <div class="personal-info">
                <div><label>姓名：<?php echo htmlspecialchars($row_user["name"]); ?></label></div>
                
                <?php if ($identity == 'student'): ?>
                    <div><label>學號：<?php echo htmlspecialchars($role_data["sid"]); ?></label></div>
                    <div><label>系所：<?php echo htmlspecialchars($role_data["department"]); ?></label></div>
                    <div><label>年級：<?php echo htmlspecialchars($role_data["grade"]); ?></label></div>
                <?php endif; ?>
                
                <?php if ($identity == 'judge'): ?>
                    <div><label>頭銜：<?php echo htmlspecialchars($role_data["title"]); ?></label></div>
                <?php endif; ?>
                
                <?php if ($identity == 'teacher'): ?>
                    <div><label>學歷：<?php echo htmlspecialchars($role_data["degree"]); ?></label></div>
                <?php endif; ?>
                
                <div><label>居住地址：<?php echo htmlspecialchars($row_user["address"]); ?></label></div>
                <div><label>聯絡電話：<?php echo htmlspecialchars($row_user["phonenumber"]); ?></label></div>
                <div><label>Email：<?php echo htmlspecialchars($row_user["email"]); ?></label></div>
            </div>
        </div>

        <!-- 操作功能按鈕區域 -->
        <div class="section">
            <div class="section-title">功能選單</div>
            <div class="buttons">
                <button onclick="location.href='../auth/profile/changepassword.php'">修改密碼</button>
                
                <?php if ($identity != 'admin'): ?>
                    <button onclick="location.href='../auth/profile/editprofile.php'">修改個資</button>
                <?php endif; ?>
                
                <?php if ($identity == 'admin'): ?>
                    <button onclick="location.href='../admin/create_announcement.php'">新增公告</button>
                    <button onclick="location.href='../admin/modify_announcement.php'">修改公告</button>
                    <button onclick="location.href='../view/allusers.php'">獲得所有使用者報表</button>
                <?php endif; ?>
                
                <?php if ($identity == 'judge'): ?>
                    <button onclick="location.href='../judge/grade.php'">評分</button>
                    <button onclick="location.href='../view/browse-project.php'">瀏覽歷屆作品</button>
                <?php endif; ?>
                
                <?php if ($identity == 'student'): ?>
                    <button onclick="location.href='../student/team/teamreg.php'">報名/修改團隊資訊</button>
                    <button onclick="location.href='../student/project_management.php'">上傳作品</button>
                    <button onclick="location.href='../view/browse-project.php'">瀏覽歷屆作品</button>
                <?php endif; ?>
                
                <?php if ($identity == 'teacher'): ?>
                    <button onclick="location.href=''">指導學生之資料/作品</button>
                    <button onclick="location.href='../view/browse-project.php'">瀏覽歷屆作品</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
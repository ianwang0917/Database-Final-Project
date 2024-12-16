<?php
    session_start();
    if(!isset($_SESSION["ssn"])){
        header("Location: index.php");
        exit();
    }
    include("connection.php");
    $select_db = @mysqli_select_db($link, "competition");
    if(!$select_db) {
        echo "<br>找不到資料庫!<br>";
    }
    $ssn = $_SESSION["ssn"];
    $sql_query_user = "SELECT * FROM `user` WHERE `ssn` = '$ssn'";
    $result_user = mysqli_query($link, $sql_query_user);
    $row_user = mysqli_fetch_assoc($result_user);
    $sql_query_judge = "SELECT * FROM `judge` WHERE `ssn` = '$ssn'";
    $result_judge = mysqli_query($link, $sql_query_judge);
    $row_judge = mysqli_fetch_assoc($result_judge);
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
        .upload-section {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .upload-section input,
        .upload-section textarea {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        #logout {
            text-decoration: none;
            color : white;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="site-name">高雄大學學生創意競賽</div>
        <div class="auth-links">
            <a href="logout.php" id="logout">登出</a>
        </div>
    </div>
    <div class="main-content">
        <!-- 個人資料區域 -->
        <div class="section">
            <div class="section-title">評審個人資料</div>
            <div class="personal-info">
                <div><label>姓名：<?php echo $row_user["name"];?></label> </div>
                <div><label>頭銜：<?php echo $row_judge["title"];?></label> </div>              
                <div><label>居住地址：<?php echo $row_user["address"];?></label> </div>
                <div><label>聯絡電話：<?php echo $row_user["phonenumber"];?></label> </div>
                <div><label>Email：<?php echo $row_user["email"];?></label> </div>
            </div>
        </div>

        <!-- 操作功能按鈕區域 -->
        <div class="section">
            <div class="section-title">功能選單</div>
            <div class="buttons">
                <button onclick="location.href='changepassword.php'">修改密碼</button>
                <button onclick="location.href=''">修改個資</button>
                <button onclick="location.href='grade.php'">評分</button>
                <button onclick="location.href='browse-projects.html'">瀏覽歷屆作品</button>
            </div>
        </div>
    </div>
</body>
</html>

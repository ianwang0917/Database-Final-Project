<?php
session_start();
if ($_SESSION["identity"] != "student") {
    header("Location: ../view/index.php");
    exit();
}

require_once("../library/connection.php");

$select_db = @mysqli_select_db($link, "competition");
if (!$select_db) {
    echo "<br>找不到資料庫!<br>";
    exit();
}

$ssn = $_SESSION["ssn"];
$sql = "SELECT `tid` FROM `student` WHERE `ssn` = '$ssn'";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
$tid = $row["tid"];

$sql = "SELECT t.name AS teamname, p.name, p.demo, p.poster, p.code, p.document FROM `piece` p INNER JOIN `team` t USING (`tid`) WHERE `tid` = '$tid'";
$result = mysqli_query($link, $sql);
$piece = mysqli_fetch_assoc($result);
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["name"], $_POST["demo"], $_POST["poster"], $_POST["code"], $_POST["document"])) {
    $piecename = $_POST["name"];
    $demo = $_POST["demo"];
    $poster = $_POST["poster"];
    $code = $_POST["code"];
    $document = $_POST["document"];

    $sql = "UPDATE `piece` SET `name` = '$piecename', `demo` = '$demo', `poster` = '$poster', `code` = '$code', `document` = '$document' WHERE `tid` = '$tid'";
    $result = mysqli_query($link, $sql);

    if ($result) {
        echo "<script>alert('作品資訊修改成功！'); window.location.href = '../view/console.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>作品管理</title>
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

        .navbar a {
            color: white;
            text-decoration: none;
        }
        .container {
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        h2 {
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
            width: 99%;
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
        .project-item button {
            margin-right: 10px;
            padding: 5px 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .filter-buttons {
            margin-top: 20px;
            text-align: center;
        }
        .filter-buttons button {
            padding: 10px 20px;
            margin: 5px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        #red {
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
            <a href="../view/console.php">回到學生頁面</a>
        </div>
    </div>

    <div class="container">
        <h1>修改作品內容</h1>
        <h2><span id="red">請先自行上傳到雲端，再將網址填入！</span>(建議使用Google雲端並記得開啟共用！)</h2>
        <form method="POST">
            <div class="form-group">
                <label for="name">隊伍名稱</label>
                <input type="text" id="name" name="teamname" value="<?= htmlspecialchars($piece["teamname"]) ?>"disabled>
            </div>
            <div class="form-group">
                <label for="name">作品名稱</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($piece["name"])?>" required>
            </div>
            <div class="form-group">
                <label for="demo">展示連結(YouTube影片或其他)</label>
                <input type="url" id="demo" name="demo" value="<?= htmlspecialchars($piece["demo"])?>" required>
            </div>
            <div class="form-group">
                <label for="poster">海報連結</label>
                <input type="url" id="poster" name="poster" value="<?= htmlspecialchars($piece["poster"])?>" required>
            </div>
            <div class="form-group">
                <label for="code">程式碼連結(可選)</label>
                <input type="url" id="code" name="code" value="<?= htmlspecialchars($piece["code"])?>">
            </div>
            <div class="form-group">
                <label for="document">文件連結(PDF)</label>
                <input type="url" id="document" name="document" value="<?= htmlspecialchars($piece["document"])?>" required>
            </div>
            <div class="form-group">
                <button type="submit">修改</button>
            </div>
        </form>
    </div>
</body>
</html>
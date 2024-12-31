<?php
    require_once("../../library/connection.php");
    session_start();
    if(!isset($_SESSION["ssn"])){
        header("Location: ../login.php");
        exit();
    }
    $table = ['admin', 'student', 'teacher', 'judge'];
    $select_db = @mysqli_select_db($link, "competition");
    if (!$select_db) {
        echo "<br>找不到資料庫!<br>";
        exit();
    }
    
    $sql = "SELECT * FROM `user` WHERE `ssn` = '$_SESSION[ssn]'";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修改個人資料</title>
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
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 24px;
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
        }
        input {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 95%;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            background-color: #007BFF;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        #home:hover {
            cursor: pointer;
        }
        #home, #logout {
            text-decoration: none;
            color : white;
        }
    </style>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</head>
<body>
	<div class="navbar">
        <div class="site-name">
            <a href="../../view/console.php" id="home">高雄大學學生創意競賽</a>
        </div>
        <div class="auth-links">
            <a href="logout.php" id="logout">登出</a>
        </div>
    </div>
    <div class="container">
        <h1>修改個人資料</h1>
        <form action="updateprofile.php" method="POST">
            <label for="">姓名</label>
            <input type="text" id="" name="name" value="<?php echo $row["name"]?>" required>

            <label for="">電話號碼</label>
            <input type="text" id="" name="phonenumber" value="<?php echo $row["phonenumber"]?>" required>

            <label for="">居住地址</label>
            <input type="text" id="" name="address" value="<?php echo $row["address"]?>" required>

            <label for="">電子信箱(Email)</label>
            <input type="text" id="" name="email" value="<?php echo $row["email"]?>" required>

            <?php
                if($_SESSION["identity"] == "student") {
                    $sql = "SELECT * FROM student WHERE ssn = '$_SESSION[ssn]'";
                    $result = mysqli_query($link, $sql);
                    $row = mysqli_fetch_assoc($result);
                    echo'
                    <label for="">系所</label>
                    <input type="text" id="" name="department" value="'.$row["department"].'" required>

                    <label for="">年級</label>
                    <input type="text" id="" name="grade" value="'.$row["grade"].'" required>

                    <label for="">學號</label>
                    <input type="text" id="" name="sid" value="'.$row["sid"].'" required>';
                } else if($_SESSION["identity"] == "teacher") {
                    $sql = "SELECT * FROM teacher WHERE ssn = '$_SESSION[ssn]'";
                    $result = mysqli_query($link, $sql);
                    $row = mysqli_fetch_assoc($result);
                    echo'
                    <label for="">學歷</label>
                    <input type="text" id="" name="degree" value="'.$row["degree"].'" required>';
                } else if($_SESSION["identity"] == "judge") {
                    $sql = "SELECT * FROM judge WHERE ssn = '$_SESSION[ssn]'";
                    $result = mysqli_query($link, $sql);
                    $row = mysqli_fetch_assoc($result);
                    echo'
                    <label for="">頭銜</label>
                    <input type="text" id="" name="title" value="'.$row["title"].'" required>';
                }
            ?>

            <button type="submit">確認修改</button>
        </form>
    </div>
</body>
</html>
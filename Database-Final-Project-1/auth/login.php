<?php
    session_start();
    require_once("../library/connection.php");
    if (isset($_SESSION["ssn"])) {
        $str = "Location: ../view/console.php";
        header($str);
        exit();
    }
?>

<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登入</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            background: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .login-container h1 {
            text-align: center;
            margin-bottom: 24px;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
        }
        .form-group input {
            width: 90%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        .form-footer {
            text-align: center;
            margin-top: 16px;
        }
        .form-footer a {
            color: #007BFF;
            text-decoration: none;
        }
        .form-footer a:hover {
            text-decoration: underline;
        }

        #wrong {
            color: red;
            text-align: center;
        }

    </style>
</head>
<body>
    <div class="login-container">
        <h1>登入</h1>
        <form action="auth.php">
            <div class="form-group">
                <label for="username">身分證字號</label>
                <input type="text" id="username" name="ssn" required>
            </div>
            <div class="form-group">
                <label for="password">密碼</label>
                <input type="password" id="password" name="password" required>
            </div>
            <?php
                error_reporting(0);
                $wrong = $_GET["wrong"];
                if($wrong)
                    echo '
            <div class="form-group" id="wrong">
                身分證或密碼錯誤!
            </div>
                ';
            ?>
            <div class="form-group">
                <button type="submit">登入</button>
            </div>
        </form>
        <div class="form-footer">
            <p>還沒有帳號嗎? <a href="../register/register.html">註冊</a></p>
            <p>返回 <a href="../view/index.php">首頁</a></p>
        </div>
    </div>
</body>
</html>
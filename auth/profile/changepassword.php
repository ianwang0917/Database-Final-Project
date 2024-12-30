<?php
    session_start();
    if(!isset($_SESSION["ssn"])){
        header("Location: index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>變更密碼</title>
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
        input[type="password"] {
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
    </style>
    <script>
        // 表單提交前確認
        function confirmChangePassword(event) {
            event.preventDefault(); // 防止表單直接提交

            // 簡單檢查新密碼是否一致
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (newPassword !== confirmPassword) {
                alert("新密碼與確認密碼不一致！");
                return;
            }

            // 顯示確認對話框
            const userConfirmed = confirm("確定修改密碼嗎？");
            if (userConfirmed) {
                // 提交表單
                const form = document.querySelector("form");
                form.submit();
            }
        }
        function goBack() {
            window.history.back();
        }
    </script>
</head>
<body>
	<div class="navbar">
        <div class="site-name" onclick="goBack()" id="home">高雄大學學生創意競賽</div>  
    </div>
    <div class="container">
        <h1>修改密碼</h1>
        <form action="change-password.php" method="POST" onsubmit="confirmChangePassword(event)">
            <label for="current-password">當前密碼</label>
            <input type="password" id="current-password" name="current-password" required>

            <label for="new-password">新密碼</label>
            <input type="password" id="new-password" name="new-password" required>

            <label for="confirm-password">確認新密碼</label>
            <input type="password" id="confirm-password" name="confirm-password" required>

            <button type="submit">確認修改</button>
        </form>
    </div>
</body>
</html>
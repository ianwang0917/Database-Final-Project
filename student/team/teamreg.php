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
    <title>隊伍報名</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: #f0f4f8;
            color: #fff;
        }
        .registration-container {
            width: 90%;
            max-width: 500px;
            background: #ffffff;
            padding: 30px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            color: #333;
            margin-top: 450px; /* Move the whole form down */
        }
        .registration-container h1 {
            text-align: center;
            margin-bottom: 24px;
            font-size: 32px;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .form-group input {
            width: 96%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
        }
        .form-group input:focus {
            border-color: #007BFF;
            outline: none;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
        }
        .form-group button {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
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
            font-weight: bold;
        }
        .form-footer a:hover {
            text-decoration: underline;
        }
        .main-title {
            text-align: center;
            font-size: 36px;
            font-weight: bold;
            color: #fff;
            margin-bottom: 20px;
        }
        #warning {
            font-size: 24px;
            color: red;
        }
        #required {
            color: red;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <h1>隊伍報名</h1>
        <form action="teamregister.php" method="POST">
            <div class="form-group">
                <label id="warning">重要：自己的身分證字號也需填入！</label>
                <label id=>有標註 <span id="required">*</span> 的欄位為必填！</label>
            </div>
            <div class="form-group">
                <label for="team-name">隊伍名稱<span id="required">*</span></label>
                <input type="text" id="name" name="name" placeholder="輸入隊伍名稱" required>
            </div>
            <div class="form-group">
                <label for="student1-id">學生1的身分證字號<span id="required">*</span></label>
                <input type="text" id="student1-id" name="student1_id" placeholder="輸入學生1的身分證字號" required>
            </div>
            <div class="form-group">
                <label for="student2-id">學生2的身分證字號<span id="required">*</span></label>
                <input type="text" id="student2-id" name="student2_id" placeholder="輸入學生2的身分證字號" required>
            </div>
            <div class="form-group">
                <label for="student3-id">學生3的身分證字號</label>
                <input type="text" id="student3-id" name="student3_id" placeholder="輸入學生3的身分證字號">
            </div>
            <div class="form-group">
                <label for="student4-id">學生4的身分證字號</label>
                <input type="text" id="student4-id" name="student4_id" placeholder="輸入學生4的身分證字號">
            </div>
            <div class="form-group">
                <label for="student4-id">學生5的身分證字號</label>
                <input type="text" id="student5-id" name="student5_id" placeholder="輸入學生5的身分證字號">
            </div>
            <div class="form-group">
                <label for="student6-id">學生6的身分證字號</label>
                <input type="text" id="student6-id" name="student6_id" placeholder="輸入學生6的身分證字號">
            </div>
            <div class="form-group">
                <label for="professor-id">指導老師的身分證字號</label>
                <input type="text" id="professor-id" name="professor_id" placeholder="輸入指導老師的身分證字號">
            </div>
            <div class="form-group">
                <button type="submit">確認報名隊伍</button>
            </div>
        </form>
        <div class="form-footer">
            <a href="../../view/console.php">回到控制面板</a>
        </div>
    </div>
</body>
</html>


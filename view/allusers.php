<?php
    session_start();
    if($_SESSION["identity"] != "admin") {
        echo "<script>alert('你無權使用此頁面！'); window.history.back();</script>";
        exit();
    }

    require_once("../library/connection.php");
    $select_db = @mysqli_select_db($link, "competition");
    if(!$select_db) {
        echo "<br>找不到資料庫!<br>";
        exit();
    }

    $tables = ["student", "teacher", "judge", "admin"];

    if(isset($_GET["identity"])) {
        $identity = $_GET["identity"];
    } else {
        header("Location: ?identity=student");
    }

    // 查詢每個表格的資料
    $results = [];
    /*foreach ($tables as $table) {
        $sql_query = "SELECT * FROM `user` INNER JOIN `$table` USING (`ssn`)";
        $query_result = mysqli_query($link, $sql_query);
    
        if ($query_result && $query_result->num_rows > 0) {
            while ($row = $query_result->fetch_assoc()) {
                $results[$table][] = $row;
            }
        }
    }*/

    $sql = "SELECT * FROM `user` INNER JOIN `$identity` USING (`ssn`) ORDER BY `ssn` ASC";
    $result = mysqli_query($link, $sql);
    if (!$result || mysqli_num_rows($result) < 0) {
        echo "資料錯誤或沒有資料！";
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Data View</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #007BFF;
            color: white;
        }

        #console-link {
            text-decoration: none;
            color: white;
        }

        .navbar .site-name {
            font-size: 28px;
            font-weight: bold;
        }

        .table-container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .table-title {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table-title > h2 {
            margin-left: 5px;
        }

        .identity-link {
            text-align: center;
        }

        .identity-link > a {
            text-decoration: none;
            margin: 0 10px;
            color: #007BFF;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #007BFF;
            color: white;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            text-align: center;
            padding: 10px;
            background-color: #333;
            color: white;
            margin-top: 20px;
        }

        .edit-button {
        display: inline-block;
        padding: 5px 10px;
        background-color: #28a745;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-size: 14px;
        transition: background-color 0.3s ease, color 0.3s ease;
        }

        .edit-button:hover {
            background-color: #218838;
            color: white;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="site-name">
            <a href="console.php" id="console-link">高雄大學學生創意競賽</a>
        </div>
    </div>
    <div class="table-container">
        <div class="table-title">
            <h2>
                <?php echo htmlspecialchars(ucfirst($identity)); ?>
            </h2>
            <div class="identity-link">
            <a href="?identity=student">學生</a>
            <a href="?identity=teacher">老師</a>
            <a href="?identity=judge">評審</a>
            <a href="?identity=admin">管理員</a>
        </div>
        </div>
        <table>
            <thead>
                <tr>
                    <?php
                    // 動態生成表格標題
                    $fields = mysqli_fetch_fields($result);
                    foreach ($fields as $field) {
                        echo "<th>" . htmlspecialchars($field->name) . "</th>";
                    }
                    echo "<th>修改</th>";
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                // 動態生成表格內容
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    foreach ($row as $value) {
                        echo "<td>" . htmlspecialchars($value) . "</td>";
                    }
                    echo "<td><a href='../admin/editprofile.php?ssn=" . urlencode($row['ssn']) . "&identity=" . urlencode($identity) . "' class='edit-button'>修改</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="footer">Powered by Admin Panel</div>
</body>
</html>
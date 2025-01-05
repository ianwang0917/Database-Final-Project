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

    $tables = ["student", "teacher", "judge", "admin", "team"];

    if(isset($_GET["identity"])) {
        $identity = $_GET["identity"];
    } else {
        header("Location: ?identity=student");
    }

    // 根據身分選擇不同的查詢
    switch($identity) {
        case 'team':
            $sql = "SELECT t.tid, t.name as '隊伍名稱', t.year as '年份', t.rank as '名次', 
                   GROUP_CONCAT(DISTINCT CONCAT(s.sid, '-', u.name) SEPARATOR ', ') as '隊伍成員',
                   CONCAT(tu.name, ' (', tt.degree, ')') as '指導老師',
                   p.name as '作品名稱', p.demo as '展示連結', p.poster as '海報連結', 
                   p.document as '文件連結'
                   FROM team t
                   LEFT JOIN student s ON t.tid = s.tid
                   LEFT JOIN user u ON s.ssn = u.ssn
                   LEFT JOIN teacher tt ON t.teacher_ssn = tt.ssn
                   LEFT JOIN user tu ON tt.ssn = tu.ssn
                   LEFT JOIN piece p ON t.tid = p.tid
                   GROUP BY t.tid
                   ORDER BY t.year DESC, t.tid ASC";
            break;
        case 'student':
            $sql = "SELECT u.ssn, u.name as '姓名', s.department as '系所', 
                   s.grade as '年級', s.sid as '學號', 
                   t.name as '所屬隊伍', u.phonenumber as '電話', 
                   u.email as '電子郵件', u.address as '地址'
                   FROM user u
                   INNER JOIN student s ON u.ssn = s.ssn
                   LEFT JOIN team t ON s.tid = t.tid
                   ORDER BY s.department, s.grade, s.sid";
            break;
        case 'teacher':
            $sql = "SELECT u.*, t.degree as '學歷',
                   GROUP_CONCAT(tm.name SEPARATOR ', ') as '指導隊伍'
                   FROM user u
                   INNER JOIN teacher t ON u.ssn = t.ssn
                   LEFT JOIN team tm ON t.ssn = tm.teacher_ssn
                   GROUP BY u.ssn";
            break;
        case 'judge':
            $sql = "SELECT u.*, j.title as '職稱', j.number as '評審編號'
                   FROM user u 
                   INNER JOIN judge j ON u.ssn = j.ssn";
            break;
        case 'admin':
            $sql = "SELECT u.*, a.job as '職位'
                   FROM user u 
                   INNER JOIN admin a ON u.ssn = a.ssn";
            break;
        default:
            echo "無效的身分選擇";
            exit();
    }

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
        /* 保留原有樣式 */
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
            overflow-x: auto;
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
            margin-bottom: 20px;
        }

        .identity-link > a {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 5px;
            text-decoration: none;
            color: #007BFF;
            border: 1px solid #007BFF;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .identity-link > a:hover,
        .identity-link > a.active {
            background-color: #007BFF;
            color: white;
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

        .link-cell a {
            color: #007BFF;
            text-decoration: none;
        }

        .link-cell a:hover {
            text-decoration: underline;
        }

        .edit-button {
            display: inline-block;
            padding: 5px 10px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .edit-button:hover {
            background-color: #218838;
        }

        .footer {
            text-align: center;
            padding: 10px;
            background-color: #333;
            color: white;
            margin-top: 20px;
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
                <?php 
                $titles = [
                    'student' => '參賽學生',
                    'teacher' => '指導老師',
                    'judge' => '評審委員',
                    'admin' => '管理員',
                    'team' => '參賽隊伍'
                ];
                echo $titles[$identity] ?? ucfirst($identity);
                ?>
            </h2>
            <div class="identity-link">
                <a href="?identity=team" <?php echo $identity == 'team' ? 'class="active"' : ''; ?>>參賽隊伍</a>
                <a href="?identity=student" <?php echo $identity == 'student' ? 'class="active"' : ''; ?>>參賽學生</a>
                <a href="?identity=teacher" <?php echo $identity == 'teacher' ? 'class="active"' : ''; ?>>指導老師</a>
                <a href="?identity=judge" <?php echo $identity == 'judge' ? 'class="active"' : ''; ?>>評審委員</a>
                <a href="?identity=admin" <?php echo $identity == 'admin' ? 'class="active"' : ''; ?>>管理員</a>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <?php
                    $fields = mysqli_fetch_fields($result);
                    foreach ($fields as $field) {
                        echo "<th>" . htmlspecialchars($field->name) . "</th>";
                    }
                    if ($identity != 'team') {
                        echo "<th>修改</th>";
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    foreach ($row as $key => $value) {
                        echo "<td class='link-cell'>";
                        if (in_array($key, ['demo', 'poster', 'document', '展示連結', '海報連結', '文件連結']) && !empty($value)) {
                            echo "<a href='" . htmlspecialchars($value) . "' target='_blank'>查看</a>";
                        } else {
                            echo htmlspecialchars($value ?: 'N/A');
                        }
                        echo "</td>";
                    }
                    if ($identity != 'team') {
                        echo "<td><a href='../admin/editprofile.php?ssn=" . urlencode($row['ssn']) . "&identity=" . urlencode($identity) . "' class='edit-button'>修改</a></td>";
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="footer">高雄大學學生創意競賽管理系統</div>
</body>
</html>
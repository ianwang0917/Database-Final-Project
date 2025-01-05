<?php
session_start();
if (!isset($_SESSION["ssn"])) {
    header("Location: index.php");
    exit();
}

require_once("../library/connection.php");
$select_db = @mysqli_select_db($link, "competition");
if (!$select_db) {
    echo "<br>找不到資料庫!<br>";
    exit();
}

// 獲取評審的 ssn
$ssn = $_SESSION["ssn"];
$sql_query_judge = "SELECT * FROM `judge` WHERE `ssn` = '$ssn'";
$result_judge = mysqli_query($link, $sql_query_judge);
$row_judge = mysqli_fetch_assoc($result_judge);

if (!$row_judge) {
    echo "無法取得評審資料。請聯繫管理員。";
    exit();
}

// 設定年份（可根據需求動態設置）
$current_year = date("Y");

// 獲取今年參賽作品資料
$sql_query_pieces = "
    SELECT p.*
    FROM `piece` AS p
    JOIN `team` AS t ON p.tid = t.tid
    WHERE t.year = '$current_year'
";
$result_pieces = mysqli_query($link, $sql_query_pieces);
if (!$result_pieces) {
    echo "無法取得參賽作品資料。";
    exit();
}

// 提交評分資料
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["pid"], $_POST["score"], $_POST["comment"])) {
        $piece_id = $_POST["pid"];
        $score = $_POST["score"];
        $comment = mysqli_real_escape_string($link, $_POST["comment"]);

        if (!is_numeric($score) || $score < 0 || $score > 100) {
            echo "<script>alert('請輸入 0 到 100 的有效分數。');</script>";
        } else {
            // 取得與作品相關的隊伍編號 tid
            $sql_query_tid = "SELECT `tid` FROM `piece` WHERE `pid` = '$piece_id'";
            $result_tid = mysqli_query($link, $sql_query_tid);
            $row_tid = mysqli_fetch_assoc($result_tid);

            if (!$row_tid) {
                echo "<script>alert('找不到隊伍編號！');</script>";
            } else {
                $tid = $row_tid['tid']; // 取得隊伍編號

                // 修改插入的 SQL 查詢，使用正確的 tid 和 ssn
                $sql_insert_score = "INSERT INTO `score` (`tid`, `ssn`, `score`, `comment`) 
                                     VALUES ('$tid', '$ssn', '$score', '$comment') 
                                     ON DUPLICATE KEY UPDATE `score` = '$score', `comment` = '$comment'";

                if (mysqli_query($link, $sql_insert_score)) {
					include("rank.php");
                    echo "<script>alert('評分與留言已成功提交！');</script>";
                } else {
                    echo "<script>alert('提交評分或留言時出錯！');</script>";
                }
            }
        }
    } else {
        echo "<script>alert('缺少必要的表單資料！');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>評分頁面</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
       /* Navbar Styling */
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
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .section-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            border-bottom: 2px solid #007BFF;
            padding-bottom: 5px;
        }
        .piece {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .piece label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .piece a {
            color: #007BFF;
            text-decoration: none;
        }
        .piece a:hover {
            text-decoration: underline;
        }
        .piece input[type="number"],
        .piece textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .piece button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            background-color: #007BFF;
            transition: background-color 0.3s;
        }
        .piece button:hover {
            background-color: #0056b3;
        }
    </style>
	<script>
        // 動態顯示選擇的作品詳細資訊
        function showPieceDetails() {
            const selectedPiece = document.getElementById('piece-select').value;
            const pieces = document.querySelectorAll('.piece-details');
            pieces.forEach(piece => {
                piece.style.display = (piece.dataset.pid === selectedPiece) ? 'block' : 'none';
            });
        }
    </script>
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
    <div class="main-content">
        <div class="section-title">評分</div>

        <!-- 作品選擇 -->
        <label for="piece-select">選擇作品：</label>
        <select id="piece-select" onchange="showPieceDetails()">
            <option value="" disabled selected>請選擇作品</option>
            <?php while ($row_piece = mysqli_fetch_assoc($result_pieces)) { ?>
                <option value="<?php echo htmlspecialchars($row_piece['pid']); ?>">
                    <?php echo htmlspecialchars($row_piece['name']); ?>
                </option>
            <?php } ?>
        </select>

        <!-- 作品詳細資料與評分 -->
        <?php
        // 重置查詢指標，重新獲取今年參賽作品的詳細資料
        mysqli_data_seek($result_pieces, 0);
        while ($row_piece = mysqli_fetch_assoc($result_pieces)) {
            ?>
            <div class="piece-details" data-pid="<?php echo htmlspecialchars($row_piece['pid']); ?>" style="display: none;">
                <form method="POST" class="piece">
                    <label>作品名稱：<?php echo htmlspecialchars($row_piece['name']); ?></label>
                    <label>
                        Demo：<?php if (!empty($row_piece['demo'])) { ?>
                            <a href="<?php echo htmlspecialchars($row_piece['demo']); ?>" target="_blank">觀看 Demo</a>
                        <?php } else { echo "尚未提供"; } ?>
                    </label>
                    <label>
                        Document：<?php if (!empty($row_piece['document'])) { ?>
                            <a href="<?php echo htmlspecialchars($row_piece['document']); ?>" target="_blank">查看 Document</a>
                        <?php } else { echo "尚未提供"; } ?>
                    </label>
                    <input type="hidden" name="pid" value="<?php echo $row_piece['pid']; ?>">
                    <label for="score">分數 (0-100)：</label>
                    <input type="number" name="score" min="0" max="100" required>
                    <label for="comment">留言：</label>
                    <textarea name="comment" rows="4" placeholder="輸入您的留言"></textarea>
                    <button type="submit">提交評分</button>
                </form>
            </div>
        <?php } ?>
    </div>
</body>
</html>


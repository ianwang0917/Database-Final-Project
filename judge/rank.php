<?php
require_once("../library/connection.php");

// 選擇資料庫
$select_db = @mysqli_select_db($link, "competition");
if (!$select_db) {
    echo "無法找到資料庫!";
    exit();
}

// 計算每個隊伍的總分並排序
$sql_query = "
    SELECT s.tid, SUM(s.score) AS total_score
    FROM score s
    GROUP BY s.tid
    ORDER BY total_score DESC, s.tid ASC
";
$result = mysqli_query($link, $sql_query);
if (!$result) {
    echo "無法計算總分，請檢查資料庫設定！";
    exit();
}

// 更新隊伍排名
$rank = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $tid = $row['tid'];

    // 更新隊伍的 rank
    $update_query = "UPDATE team SET rank = $rank WHERE tid = '$tid'";
    if (!mysqli_query($link, $update_query)) {
        echo "更新隊伍排名時出錯：隊伍 ID = $tid";
        exit();
    }

    $rank++;
}

echo "隊伍排名已成功更新！";
?>

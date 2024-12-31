<?php
    require_once("../library/connection.php");
    $select_db = @mysqli_select_db($link, "competition");
    if(!$select_db) {
        echo "<br>找不到資料庫!<br>";
        exit();
    }

    $tables = ["student", "teacher", "judge", "admin"];

    // 查詢每個表格的資料
    $results = [];
    foreach ($tables as $table) {
        $sql_query = "SELECT * FROM `$table`";
        $query_result = mysqli_query($link, $sql_query);
    
        if ($query_result && $query_result->num_rows > 0) {
            while ($row = $query_result->fetch_assoc()) {
                $results[$table][] = $row;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>使用者報表</title>
    <style>
    </style>
</head>
<body>
    <h1>使用者資料列表</h1>
    <?php foreach ($results as $table => $data): ?>
        <div class="table-container">
            <h2 style="padding: 15px;">
                <?php echo ucfirst($table); ?> 資料
            </h2>
            <table style="border-style: solid;" rules="all">
                <thead>
                    <tr>
                        <?php foreach (array_keys($data[0]) as $column): ?>
                            <th><?php echo htmlspecialchars($column); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <?php foreach ($row as $value): ?>
                                <td><?php echo htmlspecialchars($value); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
</body>
</html>

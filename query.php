<?php

require "DataBase.php";

$pdo = DataBase::connect();

$sql = "
SELECT sensor, AVG(valor) AS media
FROM (
    SELECT sensor, valor,
           ROW_NUMBER() OVER (PARTITION BY sensor ORDER BY id DESC) AS rn
    FROM sensores
) t
WHERE rn <= 30
GROUP BY sensor
";

$stmt = $pdo->query($sql);

$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($dados);
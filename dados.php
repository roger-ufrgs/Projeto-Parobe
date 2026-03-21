<?php

header("Content-Type: application/json");

require __DIR__ . "/DataBase.php";

$pdo = DataBase::connect();

$sql = "
SELECT 
    sensores.tipo,
    AVG(valor) AS media,
    MAX(valor) AS maximo,
    MIN(valor) AS minimo
FROM (
    SELECT 
        sensor_id,
        valor,
        ROW_NUMBER() OVER (PARTITION BY sensor_id ORDER BY data_leitura DESC) AS rn
    FROM leituras
) ultimas
JOIN sensores ON sensores.id = ultimas.sensor_id
WHERE rn <= 50
GROUP BY sensores.tipo
";

$stmt = $pdo->query($sql);

$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

$resultado = [];

foreach ($dados as $d) {

    $resultado[$d["tipo"]] = [
        "media"  => (float)$d["media"],
        "max"    => (float)$d["maximo"],
        "min"    => (float)$d["minimo"]
    ];

}

echo json_encode($resultado);
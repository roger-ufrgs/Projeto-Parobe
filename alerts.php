<?php

header("Content-Type: application/json");

require __DIR__ . '/DataBase.php';

$pdo = DataBase::connect();

$stmt = $pdo->query("
SELECT 
id,
temperatura,
umidade,
vibracao,
ruido,
corrente,
tensao,
status,
risco,
criado_em
FROM diagnostico
ORDER BY id DESC
LIMIT 12
");

$alertas = [];

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

    $alertas[] = [

        "id" => $row["id"],

        "temperatura" => $row["temperatura"],
        "umidade" => $row["umidade"],
        "vibracao" => $row["vibracao"],
        "ruido" => $row["ruido"],
        "corrente" => $row["corrente"],
        "tensao" => $row["tensao"],

        "status" => $row["status"],
        "risco" => $row["risco"],
        "data" => $row["criado_em"]

    ];

}

echo json_encode($alertas);
<?php

header("Content-Type: application/json");

require __DIR__ . '/DataBase.php';

$pdo = DataBase::connect();

$data = json_decode(file_get_contents("php://input"), true);

if(!$data){
    echo json_encode(["erro"=>"JSON inválido"]);
    exit;
}

$resultados = [];

foreach($data as $sensorNome => $valor){

    $stmt = $pdo->prepare("
    SELECT id FROM sensores WHERE nome = ?
    ");

    $stmt->execute([$sensorNome]);

    $sensor = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$sensor){
        $resultados[] = "sensor não encontrado: ".$sensorNome;
        continue;
    }

    $stmt = $pdo->prepare("
    INSERT INTO leituras (sensor_id, valor)
    VALUES (?, ?)
    ");

    $stmt->execute([
        $sensor['id'],
        $valor
    ]);

    $resultados[] = "salvo: ".$sensorNome;

}

echo json_encode([
    "ok"=>true,
    "resultado"=>$resultados
]);
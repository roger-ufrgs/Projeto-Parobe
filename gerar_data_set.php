<?php

require __DIR__ . '/DataBase.php';

$pdo = DataBase::connect();

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$total = 1000;

for($i = 0; $i < $total; $i++){

    $tipo = rand(1,3);

    if($tipo == 1){

        // NORMAL
        $temperatura = rand(850,1050);
        $vibracao = rand(1500,1750);
        $ruido = rand(850,950);
        $corrente = rand(1100,1300);
        $tensao = rand(1050,1200);
        $umidade = rand(1600,1850);

        $status = "NORMAL";

    }elseif($tipo == 2){

        // ATENCAO
        $temperatura = rand(1100,1350);
        $vibracao = rand(1750,2000);
        $ruido = rand(950,1150);
        $corrente = rand(1300,1600);
        $tensao = rand(1200,1450);
        $umidade = rand(1850,2100);

        $status = "ATENCAO";

    }else{

        // FALHA
        $temperatura = rand(1500,1850);
        $vibracao = rand(2000,2300);
        $ruido = rand(1200,1600);
        $corrente = rand(1700,2100);
        $tensao = rand(1500,1800);
        $umidade = rand(2100,2400);

        $status = "FALHA";

    }

    $stmt = $pdo->prepare("
    INSERT INTO dataset_ml
    (temperatura,vibracao,ruido,corrente,tensao,umidade,luminosidade,qualidade_ar,status)
    VALUES (?,?,?,?,?,?,?,?,?)
    ");

    $stmt->execute([
        $temperatura,
        $vibracao,
        $ruido,
        $corrente,
        $tensao,
        $umidade,
        0,
        0,
        $status
    ]);

}

echo "Dataset gerado com sucesso!";
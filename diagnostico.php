<?php

header("Content-Type: application/json");

require __DIR__ . '/DataBase.php';
require __DIR__ . '/vendor/autoload.php';

use Phpml\Classification\NaiveBayes;

$pdo = DataBase::connect();

/* ----------------------------
PEGAR ÚLTIMA LEITURA DE CADA SENSOR
---------------------------- */

$stmt = $pdo->query("
SELECT sensor_id, valor
FROM leituras
WHERE id IN (
    SELECT MAX(id)
    FROM leituras
    GROUP BY sensor_id
)
");

$dados = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $dados[$row['sensor_id']] = $row['valor'];
}

/* ----------------------------
MONTAR VETOR PARA ML
---------------------------- */

$sample = [

    $dados[1] ?? 0, // temperatura
    $dados[2] ?? 0, // vibracao
    $dados[3] ?? 0, // ruido
    $dados[4] ?? 0, // corrente
    $dados[5] ?? 0, // tensao
    $dados[6] ?? 0, // umidade
    $dados[7] ?? 0, // luminosidade
    $dados[8] ?? 0  // qualidade_ar

];

/* ----------------------------
CARREGAR DATASET DE TREINAMENTO
---------------------------- */

$stmt = $pdo->query("SELECT * FROM dataset_ml");

$samples = [];
$labels = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $samples[] = [

        $row['temperatura'],
        $row['vibracao'],
        $row['ruido'],
        $row['corrente'],
        $row['tensao'],
        $row['umidade'],
        $row['luminosidade'],
        $row['qualidade_ar']

    ];

    $labels[] = $row['status'];
}

/* ----------------------------
TREINAR MODELO
---------------------------- */

$classifier = new NaiveBayes();
$classifier->train($samples, $labels);

/* ----------------------------
PREVISÃO
---------------------------- */

$status = $classifier->predict($sample);

/* ----------------------------
CALCULAR RISCO
---------------------------- */

$stmt = $pdo->query("
SELECT
AVG(temperatura) t,
AVG(vibracao) v,
AVG(ruido) r,
AVG(corrente) c,
AVG(tensao) te,
AVG(umidade) u
FROM dataset_ml
WHERE status='NORMAL'
");

$normal = $stmt->fetch(PDO::FETCH_ASSOC);

$desvio = 0;

$desvio += abs($sample[0] - $normal['t']);
$desvio += abs($sample[1] - $normal['v']);
$desvio += abs($sample[2] - $normal['r']);
$desvio += abs($sample[3] - $normal['c']);
$desvio += abs($sample[4] - $normal['te']);
$desvio += abs($sample[5] - $normal['u']);

$risco = min(100, round($desvio / 50));

/* ----------------------------
RETORNAR RESULTADO
---------------------------- */
$stmt = $pdo->prepare("
INSERT INTO diagnostico (status, risco)
VALUES (?, ?)
");

$stmt = $pdo->prepare("
INSERT INTO diagnostico
(temperatura,vibracao,ruido,corrente,tensao,umidade,status,risco)
VALUES (?,?,?,?,?,?,?,?)
");

$stmt->execute([

    $sample[0],
    $sample[1],
    $sample[2],
    $sample[3],
    $sample[4],
    $sample[5],

    $status,
    $risco

]);

/* ----------------------------
APRENDIZADO AUTOMÁTICO
---------------------------- */

if (
    ($status == "NORMAL" && $risco < 20) ||
    ($status == "FALHA" && $risco > 80)
) {

    $stmt = $pdo->prepare("
INSERT INTO dataset_ml
(temperatura,vibracao,ruido,corrente,tensao,umidade,luminosidade,qualidade_ar,status)
VALUES (?,?,?,?,?,?,?,?,?)
");

    $stmt->execute([

        $sample[0],
        $sample[1],
        $sample[2],
        $sample[3],
        $sample[4],
        $sample[5],
        0,
        0,
        $status

    ]);
}

echo json_encode([
    "status" => $status,
    "risco" => $risco
]);

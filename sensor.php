<?php

$sensor = $_GET['sensor'] ?? 'temperatura';

$sensores = [

    "temperatura" => [
        "nome" => "Temperatura",
        "icone" => "bi-thermometer-half",
        "unidade" => "°C"
    ],

    "umidade" => [
        "nome" => "Umidade",
        "icone" => "bi-droplet",
        "unidade" => "%"
    ],

    "vibracao" => [
        "nome" => "Vibração",
        "icone" => "bi-activity",
        "unidade" => ""
    ],

    "corrente" => [
        "nome" => "Corrente",
        "icone" => "bi-lightning-charge",
        "unidade" => "A"
    ],

    "ruido" => [
        "nome" => "Ruído",
        "icone" => "bi-volume-up",
        "unidade" => "dB"
    ],

    "tensao" => [
        "nome" => "Tensão",
        "icone" => "bi-battery-charging",
        "unidade" => "V"
    ]

];

if(!isset($sensores[$sensor])){
    $sensor = "temperatura";
}

$dados = $sensores[$sensor];

?>

<?php require_once __DIR__ . "/fragments/_head.php"; ?>
<?php require_once __DIR__ . "/fragments/_menu.php"; ?>

<main class="main sensor-page">

<h2>
<i class="bi <?= $dados["icone"] ?>"></i>
<?= $dados["nome"] ?>
</h2>

<div class="dashboard">

    <!-- COLUNA DOS GRÁFICOS -->

    <div class="graficos">

        <div class="sensor">

            <h3>Tempo Real</h3>

            <canvas
            id="sensorChart"
            data-sensor="<?= $sensor ?>"
            data-unidade="<?= $dados["unidade"] ?>">
            </canvas>

        </div>

        <div class="sensor">

            <h3>Histórico</h3>

            <canvas id="historicoChart"></canvas>

        </div>

 </div>


    <!-- COLUNA ALERTAS -->

    <div class="alertas">

        <h3>Alertas</h3>

        <div id="historico"></div>

    </div>

</div>

<?php require_once __DIR__ . "/fragments/_conexoes.php"; ?>

</main>

<?php require_once __DIR__ . "/fragments/_footer.php"; ?>
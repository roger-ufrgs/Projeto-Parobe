<?php  
require_once __DIR__ . "/fragments/_head.php";
?>

<main class="main">

    <div class="dashboard">

        <div class="ia-control">

            <h3>Diagnóstico IA</h3>

            <button id="btnGPT" class="btn-gpt">
                GPT: DESLIGADO
            </button>

        </div>

        <!-- lista de respostas da IA -->

        <div class="ia-container">

            <h3>Histórico de Diagnósticos</h3>

            <div class="ia-lista" id="listaIA">

                <!-- cards da IA aparecem aqui -->

            </div>

        </div>

    </div>

    <?php require_once __DIR__ . "/fragments/_conexoes.php"; ?>

</main>

<?php require_once __DIR__ . "/fragments/_footer.php"; ?>
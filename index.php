        <?php  
require_once __DIR__ . "/fragments/_head.php";

?>


        <!-- ÁREA PRINCIPAL -->
        <main class="main">
            <h2>Tempo Real</h2>

            <div class="dashboard">
                <!-- SENSORES -->
                <div class="sensores">

                    <div class="sensor">
                        <h2>Temperatura</h2>
                        <canvas id="tempChart"></canvas>
                    </div>

                    <div class="sensor">
                        <h2>Umidade</h2>
                        <canvas id="umiChart"></canvas>
                    </div>

                    <div class="sensor">
                        <h2>Vibração</h2>
                        <canvas id="vibChart"></canvas>
                    </div>

                    <div class="sensor">
                        <h2>Corrente</h2>
                        <canvas id="correnteChart"></canvas>
                    </div>

                    <div class="sensor">
                        <h2>Ruído</h2>
                        <canvas id="ruidoChart"></canvas>
                    </div>

                    <div class="sensor">
                        <h2>Tensão</h2>
                        <canvas id="tensaoChart"></canvas>
                    </div>
                    <div class="sensor">
                        <h2>Ruído</h2>
                        <canvas id="ruidoChart"></canvas>
                    </div>

                    <div class="sensor">
                        <h2>Tensão</h2>
                        <canvas id="tensaoChart"></canvas>
                    </div>

                </div>

                <!-- ALERTAS -->
                <div class="alertas">

                    <h2>Alertas</h2>

                    <div class="alert-box">
                        
                    </div>

                </div>

            </div>
        <?php require_once __DIR__ . "/fragments/_conexoes.php"; ?>
        </main>

   <?php require_once __DIR__ . "/fragments/_footer.php"; ?>
import { iniciarGraficos, criarGraficoHistorico, atualizarHistorico } from "./charts.js"
import { iniciarMQTT } from "./mqtt.js"
import { init } from "./status.js"
import { verificarDiagnostico, mostrarAlertas } from "./diagnostico.js"


document.addEventListener("DOMContentLoaded", () => {

    // inicialização dos módulos
    iniciarGraficos()
    criarGraficoHistorico()
    iniciarMQTT()
    init()

    // carregar histórico do banco
    atualizarHistorico()

    setInterval(mostrarAlertas, 5000);

    // atualizar histórico a cada 30 segundos
    setInterval(atualizarHistorico, 3000)

    setInterval(verificarDiagnostico, 10000)

    // menu toggle
    const toggle = document.querySelector(".submenu-toggle")
    const submenu = document.querySelector(".submenu")

    if(toggle){
        toggle.addEventListener("click", () => {

            if (submenu.style.display === "block") {
                submenu.style.display = "none"
            } else {
                submenu.style.display = "block"
            }

        })
    }

})
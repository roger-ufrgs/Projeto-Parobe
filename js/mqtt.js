import { atualizarGrafico, tempChart, vibChart, correnteChart, ruidoChart, tensaoChart, umiChart, sensorChart } from "./charts.js"
import { salvarSensor } from "./banco.js"

let client = null

export function iniciarMQTT() {

    const options = {
        username: "parobe",
        password: "Parobe@123"
    }

    client = mqtt.connect(
        "wss://f23aed086dfa4c4badeddae4e94f75a2.s1.eu.hivemq.cloud:8884/mqtt",
        options
    )

    client.on("connect", () => {

        console.log("MQTT conectado")

        client.subscribe("parobe/sensores")

    })

    client.on("message", async (topic, message) => {

        if (topic !== "parobe/sensores") return

        try {

            const dados = JSON.parse(message.toString());


            /* --------------------------
            DASHBOARD
            -------------------------- */

            atualizarGrafico(tempChart, dados.temperatura)
            atualizarGrafico(umiChart, dados.umidade)
            atualizarGrafico(ruidoChart, dados.ruido)
            atualizarGrafico(tensaoChart, dados.tensao)
            atualizarGrafico(correnteChart, dados.corrente)
            atualizarGrafico(vibChart, dados.vibracao)

            /* --------------------------
            PÁGINA INDIVIDUAL DO SENSOR
            -------------------------- */

            const canvas = document.getElementById("sensorChart")

            if(canvas){

                const sensorAtual = canvas.dataset.sensor

                if(dados[sensorAtual] !== undefined){
                    atualizarGrafico(sensorChart, dados[sensorAtual])
                }

            }

            /* --------------------------
            SALVAR NO BANCO
            -------------------------- */

            await salvarSensor(dados)

        } catch (erro) {

            console.error("Erro ao ler JSON MQTT", erro)

        }

    })

}
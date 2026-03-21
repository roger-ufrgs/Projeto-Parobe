// =============================
// CRIAÇÃO DOS GRÁFICOS PRINCIPAIS
// =============================

// Variáveis globais para armazenar os gráficos
export let tempChart
export let umiChart
export let vibChart
export let correnteChart
export let ruidoChart
export let tensaoChart
export let sensorChart


// Função responsável por verificar quais gráficos existem na página
// e criar apenas os gráficos necessários.
export function iniciarGraficos() {

    const temp = document.getElementById("tempChart")
    const umi = document.getElementById("umiChart")
    const vib = document.getElementById("vibChart")
    const corrente = document.getElementById("correnteChart")
    const ruido = document.getElementById("ruidoChart")
    const tensao = document.getElementById("tensaoChart")
    const sensor = document.getElementById("sensorChart")

    if(temp){
        tempChart = criarGrafico(temp, "Temperatura")
    }

    if(umi){
        umiChart = criarGrafico(umi, "Umidade")
    }

    if(vib){
        vibChart = criarGrafico(vib, "Vibração")
    }

    if(corrente){
        correnteChart = criarGrafico(corrente, "Corrente")
    }

    if(ruido){
        ruidoChart = criarGrafico(ruido, "Ruído")
    }

    if(tensao){
        tensaoChart = criarGrafico(tensao, "Tensão")
    }

    if(sensor){
        const nome = sensor.dataset.sensor
        sensorChart = criarGrafico(sensor, nome)
    }

}


// =============================
// FUNÇÃO QUE CRIA UM GRÁFICO
// =============================

function criarGrafico(canvas, label){

    return new Chart(canvas.getContext("2d"), {

        type: "line",

        data: {
            labels: [],
            datasets: [{
                label: label,
                data: [],
                borderWidth: 2
            }]
        },

        options:{
            responsive:true,
            maintainAspectRatio:false
        }

    })

}


// =============================
// ATUALIZAÇÃO DOS GRÁFICOS EM TEMPO REAL
// =============================

export function atualizarGrafico(chart, valor){

    if(!chart) return

    const agora = new Date().toLocaleTimeString()

    chart.data.labels.push(agora)
    chart.data.datasets[0].data.push(valor)

    if(chart.data.labels.length > 20){
        chart.data.labels.shift()
        chart.data.datasets[0].data.shift()
    }

    chart.update()

}


// =============================
// GRÁFICO DE HISTÓRICO
// =============================

let historicoChart = null


// Cria o gráfico de histórico
export function criarGraficoHistorico(){

    const canvas = document.getElementById("historicoChart")
    if(!canvas) return

    historicoChart = new Chart(canvas.getContext("2d"), {

        type: "bar",

        data: {

            labels: ["Média", "Máximo", "Mínimo"],

            datasets: [{
                label: "Valores",
                data: [],
                backgroundColor: [
                    "#3498db",
                    "#e74c3c",
                    "#2ecc71"
                ],
                borderWidth: 1
            }]

        },

        options:{
            responsive:true,
            maintainAspectRatio:false
        }

    })

}


// =============================
// BUSCA DADOS DO MYSQL
// =============================

export async function atualizarHistorico(){

    if(!historicoChart) return

    const canvas = document.getElementById("sensorChart")
    if(!canvas) return

    const sensor = canvas.dataset.sensor

    try{

        const response = await fetch("dados.php")
        const dados = await response.json()

        console.log("Dados recebidos:", dados)

        if(!dados[sensor]) return

        const info = dados[sensor]

        historicoChart.data.datasets[0].data = [
            Number(info.media),
            Number(info.max),
            Number(info.min)
        ]

        historicoChart.update()

    }catch(e){
        console.error("Erro ao atualizar histórico:", e)
    }

}
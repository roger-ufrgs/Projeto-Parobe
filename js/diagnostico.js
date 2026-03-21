export async function verificarDiagnostico(){

    try{

        const res = await fetch("diagnostico.php")

        const dados = await res.json()

        

        if(dados.status === "FALHA" && gptEnable){
            iniciarDiagnostico(dados);
            
        }
        
        else if(dados.status === "ATENCAO" && gptEnable){
            iniciarDiagnostico(dados);
   
        }

    }catch(e){

        console.error("Erro diagnóstico:", e)

    }

}


export async function mostrarAlertas(){

   try {

    const res = await fetch("alerts.php");
    const dados = await res.json();

    const lista = document.querySelector(".alert-box");

    if (!lista) return;

    lista.innerHTML = "";

    dados.forEach(dado => {

        const div = document.createElement("div");
        div.classList.add("base-alerta", dado.status.toLowerCase());
        div.textContent =
        `${dado.status} | Risco: ${dado.risco}%`;
        const dataFormatada = new Date(dado.data).toLocaleString("pt-BR");
        div.dataset.tooltip =
        `Data: ${dataFormatada}
        Temperatura: ${dado.temperatura}
        Umidade: ${dado.umidade}
        Vibração: ${dado.vibracao}
        Ruído: ${dado.ruido}
        Corrente: ${dado.corrente}
        Tensão: ${dado.tensao}
        Risco: ${dado.risco}%`;
        lista.appendChild(div);

    });

} catch (e) {

    console.error("Erro ao carregar alertas:", e);

    }
}

async function iniciarDiagnostico(dados) {

    console.log("Iniciando diagnóstico IA com dados:",  );
    try {

        const res =await fetch("gpt.php", {
    method:"POST",
    headers:{ "Content-Type":"application/json" },
    body: JSON.stringify({
        diagnostico_id: dados.id,
        ...dados
    })
});
        const resposta = await res.json();
        alert(resposta.diagnostico);
    } catch (erro) {
        console.error("Erro ao chamar diagnóstico:", erro);
    }

}
let gptEnable = localStorage.getItem("gptEnable") === "true";

const btnGPT = document.getElementById("btnGPT");

if (btnGPT) {

        /* estado inicial do botão */
    carregarDiagnosticosIA();
    if (gptEnable) {
        btnGPT.textContent = "GPT: LIGADO";
        btnGPT.classList.add("ativo");
    }

    btnGPT.addEventListener("click", () => {

        gptEnable = !gptEnable;

        /* salva no navegador */
        localStorage.setItem("gptEnable", gptEnable);

        if (gptEnable) {

            btnGPT.textContent = "GPT: LIGADO";
            btnGPT.classList.add("ativo");

        } else {

            btnGPT.textContent = "GPT: DESLIGADO";
            btnGPT.classList.remove("ativo");

        }

    });

}

async function carregarDiagnosticosIA(){

    try{

        const res = await fetch("ia_respostas.php");

        if(!res.ok){
            throw new Error("Erro HTTP: " + res.status);
        }

        const texto = await res.text();

        if(!texto){
            console.warn("Resposta vazia do servidor");
            return;
        }

        const dados = JSON.parse(texto);

        console.log("Diagnósticos IA:", dados);

    }catch(e){

        console.error("Erro ao carregar IA:", e);

    }

}


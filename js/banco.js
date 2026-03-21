export async function salvarSensor(dados){

    try{

        const res = await fetch("salvar_dados.php", {

            method: "POST",

            headers:{
                "Content-Type":"application/json"
            },

            body: JSON.stringify(dados)

        })

        const resposta = await res.json()

    }catch(e){

        console.error("Erro ao salvar no banco:", e)

    }

}
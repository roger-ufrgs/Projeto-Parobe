export async function init() {

    try {

        const res = await fetch("services.php")
        const status = await res.json()
        atualizar("internet-status", status.internet)
        atualizar("mysql-status", status.mysql)
        atualizar("mqtt-status", status.mqtt)
        atualizar("ia-status", status.gpt)
        atualizar("esp32-status", status.esp32)

    } catch (erro) {
        console.error("Erro ao consultar status:", erro)
        atualizar("internet-status", false)
        atualizar("mqtt-status", false)
        atualizar("ia-status", false)
        atualizar("esp32-status", false)

    }

}

function atualizar(id, online) {

    const dot = document.getElementById(id)

    if (!dot) return

    dot.classList.toggle("online", online)
    dot.classList.toggle("offline", !online)

}

setInterval(init, 5000)
<?php
/*
------------------------------------------------
Autor: Roger Moraes
Arquivo: gpt.php

Descrição:
Recebe os dados dos sensores, envia para o GPT
para gerar um diagnóstico e salva a resposta
da IA na tabela diagnostico_gpt.
------------------------------------------------
*/

/* =========================
   DEPENDÊNCIAS
========================= */

require_once "gpt_client.php"; // cliente que conversa com OpenAI
require_once "DataBase.php";   // conexão com banco
require_once "WhatsAppService.php"; // serviço para enviar mensagens no WhatsApp

header("Content-Type: application/json");

/* =========================
   CONEXÃO COM BANCO
========================= */

$pdo = DataBase::connect();

/* =========================
   RECEBE DADOS DO FRONTEND
========================= */

$data = json_decode(file_get_contents("php://input"), true);


/* sensores */

$temperatura = $data["temperatura"] ?? null;
$vibracao    = $data["vibracao"] ?? null;
$ruido       = $data["ruido"] ?? null;
$corrente    = $data["corrente"] ?? null;
$tensao      = $data["tensao"] ?? null;
$umidade     = $data["umidade"] ?? null;

/* classificação gerada pelo algoritmo */

$status = $data["status"] ?? null;
$risco  = $data["risco"] ?? null;

/* id do diagnóstico salvo no banco */

$diagnosticoId = $data["diagnostico_id"] ?? null;

try {

    /* =========================
       CRIA CLIENTE GPT
    ========================= */

    $gpt = new GPTClient();

    /* =========================
       PROMPT PARA IA
    ========================= */

    $messages = [

        [
            "role" => "system",
            "content" => "Você é um sistema de diagnóstico preditivo de sensores industriais."
        ],

        [
            "role" => "user",
            "content" =>
"Os sensores detectaram um possível problema.

Status: $status
Risco: $risco %

Temperatura: $temperatura
Vibração: $vibracao
Ruído: $ruido
Corrente: $corrente
Tensão: $tensao
Umidade: $umidade

Explique brevemente o possível diagnóstico."
        ]

    ];

    /* =========================
       ENVIA PARA GPT
    ========================= */

    $resposta = $gpt->enviarMensagem($messages);

    /* pega texto da resposta */

    $respostaGPT = $resposta["choices"][0]["message"]["content"] ?? "Sem resposta";

    /* =========================
       SALVA RESPOSTA NO BANCO
    ========================= */
    WhatsAppService::enviar($respostaGPT);
    
    if ($diagnosticoId) {

        $stmt = $pdo->prepare("
            INSERT INTO diagnostico_gpt
            (diagnostico_id, resposta)
            VALUES (?, ?)
        ");

        $stmt->execute([
            $diagnosticoId,
            $respostaGPT
        ]);
    }

    /* =========================
       RETORNO PARA FRONTEND
    ========================= */

    echo json_encode([
        "status" => true,
        "diagnostico" => $respostaGPT
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => false,
        "erro" => $e->getMessage()
    ]);

}
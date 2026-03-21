<?php
/*
------------------------------------------------
Autor: Roger Moraes
Arquivo: gpt_client.php

Descrição:
Cliente responsável pela comunicação com
a API da OpenAI.
------------------------------------------------
*/

require_once "config.php";

class GPTClient
{

    private string $url;
    private string $apiKey;

    public function __construct()
    {
        $this->url = "https://api.openai.com/v1/chat/completions";
        $this->apiKey = $_ENV["OPENAI_KEY"];
    }

    public function enviarMensagem(array $messages)
    {

        $payload = [
            "model" => "gpt-4o-mini",
            "messages" => $messages
        ];

        $ch = curl_init($this->url);

        curl_setopt_array($ch, [

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,

            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer " . $this->apiKey
            ],

            CURLOPT_POSTFIELDS => json_encode($payload)

        ]);

        $response = curl_exec($ch);

        if ($response === false) {

            throw new Exception(curl_error($ch));

        }

        curl_close($ch);

        return json_decode($response, true);

    }

}
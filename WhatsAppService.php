<?php
/*
------------------------------------------------
Autor: Roger Moraes
Arquivo: whatsapp_service.php

Descrição:
Serviço responsável por enviar mensagens
via WhatsApp usando CallMeBot API
------------------------------------------------
*/

require_once "config.php";

class WhatsAppService
{

    public static function enviar($mensagem)
    {

        $phone = $_ENV["WHATS_PHONE"];
        $apikey = $_ENV["WHATS_APIKEY"];

        $url = "https://api.callmebot.com/whatsapp.php?"
            . "phone=" . $phone
            . "&text=" . urlencode($mensagem)
            . "&apikey=" . $apikey;

        error_log("Enviando mensagem para WhatsApp: " . $url);
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return $status === 200;

    }

}
<?php

header("Content-Type: application/json");

require_once "DataBase.php";

/* =========================
   INTERNET
========================= */

function testarInternet()
{
    try {

        $socket = @fsockopen("google.com", 80, $errno, $errstr, 3);

        if ($socket) {
            fclose($socket);
            return true;
        }

        return false;

    } catch (Throwable $e) {

        return false;

    }
}

/* =========================
   MYSQL
========================= */

function testarMysql()
{
    try {

        $conn = DataBase::connect();
        $conn->query("SELECT 1");

        return true;

    } catch (Throwable $e) {

        return false;

    }
}

/* =========================
   MQTT
========================= */

function testarMQTT()
{
    try {

        $host = "f23aed086dfa4c4badeddae4e94f75a2.s1.eu.hivemq.cloud";
        $port = 8883;

        $socket = @fsockopen($host, $port, $errno, $errstr, 3);

        if ($socket) {
            fclose($socket);
            return true;
        }

        return false;

    } catch (Throwable $e) {

        return false;

    }
}

/* =========================
   GPT
========================= */

function testarGPT()
{
    try {

        $socket = @fsockopen("api.openai.com", 443, $errno, $errstr, 3);

        if ($socket) {
            fclose($socket);
            return true;
        }

        return false;

    } catch (Throwable $e) {

        return false;

    }
}

/* =========================
   ESP32
========================= */

function testarESP32()
{

    try {

        $url = "http://192.168.1.29";

        $context = stream_context_create([
            "http" => [
                "timeout" => 2
            ]
        ]);

        $response = @file_get_contents($url, false, $context);

        return $response !== false;

    } catch (Throwable $e) {

        return false;

    }

}

/* =========================
   STATUS FINAL
========================= */

function getStatus()
{
    return [
        "internet" => testarInternet(),
        "mysql" => testarMysql(),
        "mqtt" => testarMQTT(),
        "gpt" => testarGPT(),
        "esp32" => testarESP32()
    ];
}

echo json_encode(getStatus());
<?php

require_once "config.php";

class DataBase
{

    private static $conn;

    public static function connect()
    {

        if (!self::$conn) {

            $host = $_ENV["DB_HOST"];
            $db   = $_ENV["DB_NAME"];
            $user = $_ENV["DB_USER"];
            $pass = $_ENV["DB_PASS"];
            $charset = $_ENV["DB_CHARSET"];

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

            try {

                self::$conn = new PDO($dsn, $user, $pass);

                self::$conn->setAttribute(
                    PDO::ATTR_ERRMODE,
                    PDO::ERRMODE_EXCEPTION
                );

                self::$conn->setAttribute(
                    PDO::ATTR_DEFAULT_FETCH_MODE,
                    PDO::FETCH_ASSOC
                );

            } catch (PDOException $e) {

                throw $e;

            }

        }

        return self::$conn;

    }

}
<?php

namespace stockalignment\Core;

use PDO;
use PDOException;


class Database
{

    private static $instance = null;
    private $pdo;
    private $SAPPort;
    private $SAPIP;
    private $SAPUser;
    private $SAPPword;
    private $DBASE;

    private function __construct()
    {
        $host = getenv('DB_HOST'); // Use environment variables for secrets
        $dbname = getenv('DB_NAME');
        $username = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');

        $host = "10.0.120.180";
        // $host = "localhost";


        // detect cli php_sapi_name();

        $absolutePath =  __FILE__;

        $this->SAPIP = "10.0.220.168";


        $dbname = "";
        if (
            strpos($_SERVER['HTTP_HOST'], "localhost") !== false || strpos($_SERVER['REQUEST_URI'], "localhost") !== false
            || strpos(strtoupper($absolutePath), "QAS") !== FALSE
        ) {
            $dbname = "_qas";
            $this->SAPPort = "8001";
            $this->SAPIP = "10.0.220.165";
        }
        $this->SAPPort = "8002";
        $this->SAPIP = "10.0.220.168";
        $dbname = "ccms" . $dbname;
        $dbname = "uratexportal_DOPDBQAS";

        $this->DBASE = $dbname;

        $this->SAPUser = "mis_api";
        $this->SAPPword = "ur@t3x";



        $username = "uratexportal";
        $password = "uratex@1968";

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error connecting to database: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function getPdo()
    {
        return $this->pdo;
    }
    public function getSAPPort()
    {
        return $this->SAPPort;
    }
    public function getSAPIP()
    {
        return $this->SAPIP;
    }
    public function getDBase()
    {
        return $this->DBASE;
    }
    public function getSAPUser()
    {
        return $this->SAPUser;
    }
    public function getSAPPword()
    {
        return $this->SAPPword;
    }

    public function getSQLRow(String $sql, array $params)
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}

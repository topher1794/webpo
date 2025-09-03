<?php

namespace webpo\Core;

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

        // $host = "localhost";


        // detect cli php_sapi_name();

        $absolutePath =  __FILE__;

        $host = "10.0.0.9";
        $username = "topher";
        $password = "t0p_Jul2014";

        $dbname = "it";
        if (
            // strpos($_SERVER['HTTP_HOST'], "localhost") !== false || strpos($_SERVER['REQUEST_URI'], "localhost") !== false
            strpos(strtoupper(SUFFIX_QAS), "QAS") !== FALSE
            || 
            strpos(strtoupper($absolutePath), "QAS") !== FALSE
        ) {
            $dbname .= "_qas";
          
        }
        try {

            $dsn = "pgsql:host=$host;port=5432;dbname=$dbname;";

		// make a database connection
            $this->pdo = new PDO(
                $dsn,
                $username,
                $password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

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

    public function getSQLAllRows(String $sql, array $params)
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}

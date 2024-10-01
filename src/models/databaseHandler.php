<?php
include_once "DatabaseConfig.php";
class DatabaseHandler
{
    private static $instance = null;
    private $connection;
    private $host;
    private $username;
    private $password;
    private $dbName;

    private function __construct()
    {
        $this->host = DATABASE_HOST;
        $this->username = DATABASE_USERNAME;
        $this->password = DATABASE_PASSWORD;
        $this->dbName = DATABASE_NAME;
        $this->connection = new mysqli($this->host, $this->username, $this->password);

        if($this->connection->connect_error) {
            echo "connection to database failed";
            die("Connection failed: ". $this->connection->connect_error);
        }
    }

    public static function Connect()
    {
        if(!self::$instance)
        {
            self::$instance = new Self;
        }
        return self::$instance;
    }

    public function GetConnection()
    {
      return new mysqli($this->host, $this->username, $this->password, $this->dbName);
    }

    public function CloseConnection() {
       $this->connection = null;
       self::$instance = null;
    }
}
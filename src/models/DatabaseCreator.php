<?php
include_once 'DatabaseConfig.php';
include 'ItemDao.php';
include 'OrderDao.php';
include 'OrderLineDao.php';
include 'UserDao.php';

define('TABLES', [
    'items'=> [
                'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
                'title' => 'VARCHAR(50) NOT NULL',
                'description' => 'VARCHAR(300) NOT NULL',
                'year' => 'YEAR UNSIGNED NOT NULL',
                'price' => 'DECIMAL(5,2) UNSIGNED NOT NULL',
                'type' => 'ENUM("CD", "VINYL", "CASETTE")',
                'stock' => 'INT(6) UNSIGNED NOT NULL',
                'date_added' => 'DATE NOT NULL'
            ],
    'images' => [
                'item_id' => 'INT(6) UNSIGNED NOT NULL',
                'image' => 'BLOB NOT NULL',
                '' => 'FOREIGN KEY (item_id) REFERENCES items(id)'
        ],
    'artists'=> [
                'name' => 'VARCHAR(50) NOT NULL PRIMARY KEY',
                'item_id' => 'INT(6) UNSIGNED NOT NULL',
                '' => 'FOREIGN KEY (item_id) REFERENCES items(id)'
            ],
    'genres'=> [
                'name' => 'VARCHAR(50) NOT NULL PRIMARY KEY',
                'item_id' => 'INT(6) UNSIGNED NOT NULL',
                '' => 'FOREIGN KEY (item_id) REFERENCES items(id)'
            ],
    'users'=> [
                'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
                'name' => 'VARCHAR(50) NOT NULL',
                'email' => 'VARCHAR(80) NOT NULL UNIQUE',
                'password' => 'VARCHAR(50) NOT NULL',
                'date_of_birth' => 'DATE NOT NULL',
                'gender' => 'VARCHAR(30) NOT NULL',
                'search_criteria' => 'VARCHAR(80) NULL',
                'admin' => 'BOOLEAN NOT NULL',
            ],  
    'orders'=> [
                'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
                'user_id' => 'INT(6) UNSIGNED NOT NULL', 
                'date' => 'DATE NOT NULL',
                '' => 'FOREIGN KEY (user_id) REFERENCES users(id)'
            ],
    'order_lines'=> [
                'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
                'order_id' => 'INT(6) UNSIGNED NOT NULL',
                'item_id' => 'INT(6) UNSIGNED NOT NULL',
                'amount' => 'INT(6) UNSIGNED NOT NULL',
                '' => 'FOREIGN KEY (item_id) REFERENCES items(id)',
                '' => 'FOREIGN KEY (order_id) REFERENCES orders(id)'
            ],
    'item_artist' => [
                'item_id' => 'INT(6) UNSIGNED NOT NULL',
                'artist_name' => 'VARCHAR(50) NOT NULL',
                '' => 'FOREIGN KEY (item_id) REFERENCES items(id)',
                '' => 'FOREIGN KEY (artist_name) REFERENCES artists(name)'
    ],
    'item_genre' => [
                'item_id' => 'INT(6) UNSIGNED NOT NULL',
                'genre_name' => 'VARCHAR(50) NOT NULL',
                '' => 'FOREIGN KEY (item_id) REFERENCES items(id)',
                '' => 'FOREIGN KEY (genre_name) REFERENCES genres(name)'
    ]

]);

class DatabaseCreator
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
    
    public function CreateDatabase()
    {
        $sql = "CREATE DATABASE IF NOT EXISTS ". $this->dbName;
        if($this->connection->query($sql) === FALSE)
        {
            echo "Error creating database: ". $this->connection->error;
        }
        $this->connection->close();
        $this->CreateTables();
    }

    public function DropDatabase()
    {
        $sql = "DROP DATABASE IF EXISTS ". $this->dbName;
        if($this->connection->query($sql) === FALSE)
        {
            echo "Error dropping database: ". $this->connection->error;
        }
    }

    private function CreateTables()
    {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->dbName);
        if($this->connection->connect_error)
        {
            die("Connection failed: ". $this->connection->connect_error);
        }
        foreach(TABLES as $tableKey => $tableValue)
        {
            $sql = "CREATE TABLE IF NOT EXISTS ". $tableKey. " (";
            foreach($tableValue as $key => $value)
            {
                $sql.= "\n" . $key . " " . $value . ",";
            }
            $sql = rtrim($sql, ",");
            $sql.= ")";

            if($this->connection->query($sql) === FALSE)
            {
                echo "Error creating table: ". $this->connection->error;
            }
        }
        $this->connection->close();
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

    public function CreateMockUsers()
    {

    }

    public function CreateMockItems()
    {

    }

    public function CreateMockOrders()
    {

    }

    public function CreateMockOrderLines()
    {

    }
}

$db = DatabaseCreator::Connect();
$db->DropDatabase();
$db->CreateDatabase();
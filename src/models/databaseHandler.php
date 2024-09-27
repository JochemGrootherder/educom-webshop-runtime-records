<?php
include 'DataHandlerInterface.php';
include 'DataTypes.php';

define('TABLES', [
    'Items'=> ['id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
                'title' => 'VARCHAR(50) NOT NULL',
                'description' => 'VARCHAR(50) NOT NULL',
                'year' => 'YEAR UNSIGNED NOT NULL',
                'price' => 'DECIMAL(5,2) UNSIGNED NOT NULL',
                'type' => 'ENUM("CD", "VINYL", "CASETTE")',
                'stock' => 'INT(6) UNSIGNED NOT NULL',
                'dateAdded' => 'DATE NOT NULL'
            ],
    'Artists'=> ['name' => 'VARCHAR(50) NOT NULL PRIMARY KEY',
                'itemId' => 'INT(6) UNSIGNED NOT NULL'],
    'Genres'=> ['name' => 'VARCHAR(50) NOT NULL PRIMARY KEY',
                'itemId' => 'INT(6) UNSIGNED NOT NULL'],
    'Orders'=> ['id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
                'userId' => 'INT(6) UNSIGNED NOT NULL',
                'date' => 'DATE NOT NULL'
            ],
    'OrderLines'=> ['id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
                'orderId' => 'INT(6) UNSIGNED NOT NULL',
                'itemId' => 'INT(6) UNSIGNED NOT NULL',
                'amount' => 'INT(6) UNSIGNED NOT NULL'],
    'Users'=> ['id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
                'name' => 'VARCHAR(50) NOT NULL',
                'email' => 'VARCHAR(80) NOT NULL',
                'password' => 'VARCHAR(50) NOT NULL',
                'dateOfBirth' => 'DATE NOT NULL',
                'gender' => 'VARCHAR(30) NOT NULL',
                'searchCriteria' => 'VARCHAR(80) NULL',
                'admin' => 'BOOLEAN NOT NULL',
            ],  

]);

class DatabaseHandler implements DataHandlerInterface
{
    private static $instance = null;
    private $connection;
    private $host = '127.0.0.1';
    private $username = 'root';
    private $password = '';
    private $dbName = 'myDB';

    private function __construct()
    {

        $this->connection = new mysqli($this->host, $this->username, $this->password);

        if($this->connection->connect_error) {
            echo "connection to database failed";
            die("Connection failed: ". $this->connection->connect_error);
        }
        $this->CreateDatabase();
    }

    public function GetUsers()
    {
        $connection = new mysqli($this->host, $this->username, $this->password, $this->dbName);
        $query = "SELECT * FROM Items";
        $result = $connection->query($query);
        return $result;
    }
    
    private function CreateDatabase()
    {
        $sql = "CREATE DATABASE IF NOT EXISTS ". $this->dbName;
        if($this->connection->query($sql) === FALSE)
        {
            echo "Error creating database: ". $this->connection->error;
        }
        $this->connection->close();
        $this->CreateTables();
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

    public function CreateUser(User $user)
    {
        echo 'Create user' . $user;
    }

    public function UpdateUser($user)
    {

    }
    public function DeleteUser($id)
    {

    }
    public function GetUserById($id)
    {

    }
    public function GetUserByEmail($email)
    {

    }

    public function CreateItem($user)
    {

    }
    public function UpdateItem($user)
    {

    }
    public function DeleteItem($id)
    {

    }
    public function GetItem($id)
    {

    }
    public function GetItems()
    {
        
    }
    public function GetItemsByFilter($filter)
    {

    }

    public function CreateOrder($order)
    {

    }
    public function UpdateOrder($order)
    {

    }
    public function DeleteOrder($id)
    {

    }
    public function GetOrder($id)
    {

    }
    public function CreateOrderLine($orderLine)
    {

    }
    public function UpdateOrderLine($orderLine)
    {

    }
    public function DeleteOrderLine($id)
    {

    }
    public function GetOrderLine($id)
    {

    }
}
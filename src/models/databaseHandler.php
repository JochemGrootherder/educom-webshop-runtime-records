<?php
include 'DataHandlerInterface.php';

define('TABLES', [
    'items'=> ['id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
                'title' => 'VARCHAR(50) NOT NULL',
                'description' => 'VARCHAR(300) NOT NULL',
                'year' => 'YEAR UNSIGNED NOT NULL',
                'price' => 'DECIMAL(5,2) UNSIGNED NOT NULL',
                'type' => 'ENUM("CD", "VINYL", "CASETTE")',
                'stock' => 'INT(6) UNSIGNED NOT NULL',
                'date_added' => 'DATE NOT NULL'
            ],
    'artists'=> ['name' => 'VARCHAR(50) NOT NULL PRIMARY KEY',
                'item_id' => 'INT(6) UNSIGNED NOT NULL'],
    'genres'=> ['name' => 'VARCHAR(50) NOT NULL PRIMARY KEY',
                'item_id' => 'INT(6) UNSIGNED NOT NULL'],
    'orders'=> ['id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
                'user_id' => 'INT(6) UNSIGNED NOT NULL',
                'date' => 'DATE NOT NULL'
            ],
    'orderLines'=> ['id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
                'order_id' => 'INT(6) UNSIGNED NOT NULL',
                'item_id' => 'INT(6) UNSIGNED NOT NULL',
                'amount' => 'INT(6) UNSIGNED NOT NULL'],
    'users'=> ['id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
                'name' => 'VARCHAR(50) NOT NULL',
                'email' => 'VARCHAR(80) NOT NULL UNIQUE',
                'password' => 'VARCHAR(50) NOT NULL',
                'date_of_birth' => 'DATE NOT NULL',
                'gender' => 'VARCHAR(30) NOT NULL',
                'search_criteria' => 'VARCHAR(80) NULL',
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
        $query = "SELECT * FROM users";
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

    private function DropDatabase()
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

    private function CreateCreateSqlStatement($tableName, $object, $duplicateKey = "")
    {
        $sql = "";
        $keys = "";
        $keys .= implode(",", array_keys((array)$object));
        $values = "";
        $values.= "'" . implode("','", (array)$object) . "'";
        $sql .= "INSERT INTO ". $tableName. " (";
        $sql .= $keys;
        $sql .= ") VALUES (";
        $sql .= $values;
        $sql.= ")";
        return $sql;
    }

    public function CreateUser(User $user)
    {
        $sql = $this->CreateCreateSqlStatement("users", $user, "");
        $connection = $this->GetConnection();
        if($connection->query($sql) === TRUE)
        {
            echo "User created successfully";
        }
        else
        {
            echo "Error creating user: ". $connection->error;
        }
    }

    public function UpdateUser(User $user)
    {

    }
    public function DeleteUser(int $id)
    {

    }
    public function GetUserById(int $id)
    {

    }
    public function GetUserByEmail(string $email)
    {

    }

    public function CreateItem(Item $item)
    {

    }
    public function UpdateItem(Item $item)
    {

    }
    public function DeleteItem(int $id)
    {

    }
    public function GetItem(int $id)
    {

    }
    public function GetItems()
    {
        
    }
    public function GetItemsByFilter(string $filter)
    {

    }

    public function CreateOrder(Order $order)
    {
        $sql = $this->CreateCreateSqlStatement("orders", $order, "");
        $connection = $this->GetConnection();
        if($connection->query($sql) === TRUE)
        {
            echo "order created successfully";
        }
        else
        {
            echo "Error creating order: ". $connection->error;
        }

    }
    public function UpdateOrder(Order $order)
    {

    }
    public function DeleteOrder(int $id)
    {

    }
    public function GetOrder(int $id)
    {

    }
    public function CreateOrderLine(OrderLine $orderLine)
    {

    }
    public function UpdateOrderLine(OrderLine $orderLine)
    {

    }
    public function DeleteOrderLine(int $id)
    {

    }
    public function GetOrderLine(int $id)
    {

    }
}
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
    'order_lines'=> ['id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
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

    private function CreateCreateSqlStatement($tableName, $object)
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

    private function CreateUpdateSqlStatement($tableName, $object, $updateKey)
    {
        $sql = "";
        $sets = "";
        foreach($object as $key => $value)
        {
            $sets.= $key. "='". $value. "',";
        }
        $sets = rtrim($sets, ",");
        $sql.= "UPDATE ". $tableName. " SET ";
        $sql.= $sets;
        $sql.= " WHERE ". $updateKey . "= '" . $object->$updateKey . "'";
        return $sql;
    }

    private function CreateDeleteSqlStatement($tableName, $keyName, $keyValue)
    {
        $sql = "DELETE FROM ". $tableName. " WHERE ". $keyName. "= '". $keyValue. "'";
        return $sql;
    }

    private function ExecuteSqlStatement($sql, $action)
    {
        $connection = $this->GetConnection();
        if($connection->query($sql) === TRUE)
        {
            echo $action ." successfully";
        }
        else
        {
            echo "Error: ". $action . "<br>". $connection->error;
        }
    }

    public function CreateUser(User $user)
    {
        $sql = $this->CreateCreateSqlStatement("users", $user, "");
        $this->ExecuteSqlStatement($sql, "CreateUser");
    }

    public function UpdateUser(User $user)
    {
        $sql = $this->CreateUpdateSqlStatement("users", $user, "id");
        $this->ExecuteSqlStatement($sql, "UpdateUser");
    }
    public function DeleteUser(int $id)
    {
        $sql = $this->CreateDeleteSqlStatement("users", "id", $id);
        $this->ExecuteSqlStatement($sql, "DeleteUser");
    }
    public function GetUserById(int $id)
    {

    }
    public function GetUserByEmail(string $email)
    {

    }

    public function CreateItem(Item $item)
    {
        $sql = $this->CreateCreateSqlStatement("items", $item, "");
        $this->ExecuteSqlStatement($sql, "CreateItem");
    }

    public function UpdateItem(Item $item)
    {
        $this->CreateUpdateSqlStatement("items", $item, "id");
        $this->ExecuteSqlStatement($sql, "UpdateItem");

    }
    public function DeleteItem(int $id)
    {
        $sql = $this->CreateDeleteSqlStatement("items", "id", $id);
        $this->ExecuteSqlStatement($sql, "DeleteItem");
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
        $this->ExecuteSqlStatement($sql, "CreateOrder");
    }

    public function UpdateOrder(Order $order)
    {
        $this->CreateUpdateSqlStatement("orders", $order, "id");
        $this->ExecuteSqlStatement($sql, "UpdateOrder");
    }
    public function DeleteOrder(int $id)
    {
        $sql = $this->CreateDeleteSqlStatement("orders", "id", $id);
        $this->ExecuteSqlStatement($sql, "DeleteOrder");
    }
    public function GetOrder(int $id)
    {

    }
    public function CreateOrderLine(OrderLine $orderLine)
    {
        $sql = $this->CreateCreateSqlStatement("orders", $orderLine, "");
        $this->ExecuteSqlStatement($sql, "CreateOrderLine");

    }
    public function UpdateOrderLine(OrderLine $orderLine)
    {
        $this->CreateUpdateSqlStatement("order_lines", $orderLine, "id");
        $this->ExecuteSqlStatement($sql, "UpdateOrderLine");
    }
    public function DeleteOrderLine(int $id)
    {
        $sql = $this->CreateDeleteSqlStatement("order_lines", "id", $id);
        $this->ExecuteSqlStatement($sql, "DeleteOrderLine");
    }
    public function GetOrderLine(int $id)
    {

    }
}
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

    private function CreatePrepareInsertSqlStatement($tableName, $object)
    {
        $keys = "";
        $keys .= implode(",", array_keys((array)$object));

        $prepared_sql = "INSERT INTO " . $tableName . "(";
        $prepared_sql .= $keys;
        $prepared_sql .= ") VALUES (";
        $in = str_repeat('?, ', count((array)$object)-1) . '?';
        $prepared_sql .= $in;
        $prepared_sql.= ")";
        return $prepared_sql;
    }

    private function CreatePrepareUpdateSqlStatement($tableName, $object, $updateKey)
    {
        $prepared_sql = "";
        $sets = "";
        foreach($object as $key => $value)
        {
            $sets.= $key. "= ?, ";
        }
        $sets = rtrim($sets, ", ");
        $prepared_sql.= "UPDATE ". $tableName. " SET ";
        $prepared_sql.= $sets;
        $prepared_sql.= " WHERE ". $updateKey . "= '" . $object->$updateKey . "'";
        return $prepared_sql;
    }

    private function CreateDeleteSqlStatement($tableName, $keyName, $keyValue)
    {
        $sql = "DELETE FROM ". $tableName. " WHERE ". $keyName. "= '". $keyValue. "'";
        return $sql;
    }

    private function ExecuteSqlStatement($sql, $action)
    {
        $connection = $this->GetConnection();
        $result = $connection->query($sql);
        var_dump($result);
        if($result === TRUE)
        {
            echo $action ." successfully";
        }
        else
        {
            echo "Error: ". $action . "<br>". $connection->error;
        }
        return $result;
        $connection->close();
    }

    private function BindToStatement(&$statement, $object)
    {
        $data = [];
        $bindIdentifiers = "";

        foreach((array)$object as $key => $value)
        {
            array_push($data, $value);
            $type = gettype($value);
            switch($type)
            {
                case "integer":
                case "boolean":
                    $bindIdentifiers .= "i";
                    break;
                    
                case "string":
                    $bindIdentifiers .= "s";
                    break;

                case "double":
                case "float":
                    $bindIdentifiers .= "d";
                    break;

                default: 
                    $bindIdentifiers .= "b";
                break;
            }
        }
        $statement->bind_param($bindIdentifiers, ...$data);
    }

    private function ExecutePreparedStatement($sql, $object)
    {
        $connection = $this->GetConnection();
        $statement = $connection->prepare($sql);
        if($object != null)
        {
            $this->BindToStatement($statement, $object);
        }
        $statement->execute();
        $statement->close();
        $connection->close();
    }

    public function CreateUser(User $user)
    {
        $sql = $this->CreatePrepareInsertSqlStatement("users", $user, "");
        $this->ExecutePreparedStatement($sql, $user);
    }

    public function UpdateUser(User $user)
    {
        $sql = $this->CreatePrepareUpdateSqlStatement("users", $user, "id");
        $this->ExecutePreparedStatement($sql, $user);
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
        echo $email;
        $sql = "SELECT * FROM users WHERE email = '" .$email . "'";
        return $this->ExecuteSqlStatement($sql, "GetUserByEmail");
    }

    public function CreateItem(Item $item)
    {
        $sql = $this->CreatePrepareInsertSqlStatement("items", $item, "");
        $this->ExecutePreparedStatement($sql, $item);

    }

    public function UpdateItem(Item $item)
    {
        $this->CreatePrepareUpdateSqlStatement("items", $item, "id");
        $this->ExecutePreparedStatement($sql, $item);

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
        $sql = $this->CreatePrepareInsertSqlStatement("orders", $order, "");
        $this->ExecutePreparedStatement($sql, $order);
    }

    public function UpdateOrder(Order $order)
    {
        $sql = $this->CreatePrepareUpdateSqlStatement("orders", $order, "id");
        $this->ExecutePreparedStatement($sql, $order);
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
        $sql = $this->CreatePrepareInsertSqlStatement("orders", $orderLine, "");
        $this->ExecutePreparedStatement($sql, $orderLine);

    }
    public function UpdateOrderLine(OrderLine $orderLine)
    {
        $this->CreatePrepareUpdateSqlStatement("order_lines", $orderLine, "id");
        $this->ExecutePreparedStatement($sql, $orderLine);
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
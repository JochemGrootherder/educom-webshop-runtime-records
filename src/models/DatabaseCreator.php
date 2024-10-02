<?php
include_once 'DatabaseConfig.php';
include 'DAOs/ItemDao.php';
include 'DAOs/OrderDao.php';
include 'DAOs/OrderLineDao.php';
include 'DAOs/UserDao.php';
include_once 'CRUD.php';

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
                'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
                'name' => 'VARCHAR(50) NOT NULL UNIQUE'
            ],
    'genres'=> [
                'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
                'name' => 'VARCHAR(50) NOT NULL UNIQUE'
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
    'item_artists' => [
                'item_id' => 'INT(6) UNSIGNED NOT NULL',
                'artist_id' => 'INT(6) UNSIGNED NOT NULL',
                '' => 'FOREIGN KEY (item_id) REFERENCES items(id)',
                '' => 'FOREIGN KEY (artist_id) REFERENCES artists(id)',
    ],
    'item_genres' => [
                'item_id' => 'INT(6) UNSIGNED NOT NULL',
                'genre_id' => 'INT(6) UNSIGNED NOT NULL',
                '' => 'FOREIGN KEY (item_id) REFERENCES items(id)',
                '' => 'FOREIGN KEY (genre_id) REFERENCES genres(id)'
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
        $userDAO = new UserDAO();
        $adminUser = new User(
            0,
            'Admin',
            'admin@example.com',
            'Admin123!',
            '1990-01-01',
            'Male',
            '',
            True
        );
        $user1 = new User(
            0,
            'test',
            'test@test.com',
            'Test123!',
            '1988-01-01',
            'Male',
            '',
            False
        );
        $user2 = new User(
            0,
            'Kroket',
            'Kroket@test.com',
            'Kroket123!',
            '1988-01-01',
            'Male',
            '',
            False
        );

        $userDAO->Create($adminUser);
        $userDAO->Create($user1);
        $userDAO->Create($user2);
    }

    public function CreateMockItems()
    {
        $itemDAO = new ItemDAO();
        $dateAdded = date_create();
        $dateAdded = date_format($dateAdded, "Y\m\d");
        $item1 = new Item(
            0,
            'A night at the opera',
            ['Queen', 'Queen'],
            ['rock', 'classic rock'],
            "A band's hit album by the British rock band Queen.",
            1975,
            12.99,
            ItemTypes::CD,
            50,
            $dateAdded
        );
        $item2 = new Item(
            0,
            'Trench',
            ['Twenty one pilots'],
            ['rock', 'pop'],
            "Great album",
            2016,
            24.99,
            ItemTypes::VINYL,
            60,
            $dateAdded
        );
        $item3 = new Item(
            0,
            'Wish you were here',
            ['Pink floyd'],
            ['rock classics'],
            "Great album",
            1975,
            18.99,
            ItemTypes::VINYL,
            15,
            $dateAdded
        );
        $itemDAO->Create($item1);
        $itemDAO->Create($item2);
        $itemDAO->Create($item3);
    }

    public function CreateMockOrders()
    {
        $orderDAO = new OrderDAO();
    }

    public function CreateMockOrderLines()
    {
        $orderLinesDao = new OrderLinesDao();
    }
}

$db = DatabaseCreator::Connect();
$db->DropDatabase();
$db->CreateDatabase();

$db->CreateMockUsers();
$db->CreateMockItems();

$crud = new CRUD();
$createdUsers = $crud->GetAllFromTable("users");
echo "CREATED USERS: (" . count($createdUsers) . ")" ;
echo "<br>";
foreach ($createdUsers as $createdUser)
{
    var_dump($createdUser);
    echo "<br>";
}

$createdItems = $crud->GetAllFromTable("items");
echo "CREATED ITEMS: (" . count($createdUsers) . ")" ;
echo "<br>";
foreach ($createdItems as $createdItem)
{
    var_dump($createdItem);
    echo "<br>";
}

$createdOrders = $crud->GetAllFromTable("orders");
echo "CREATED ORDERS: (" . count($createdOrders) . ")" ;
echo "<br>";
foreach ($createdOrders as $createdOrder)
{
    var_dump($createdOrder);
    echo "<br>";
}

$createdOrderLines = $crud->GetAllFromTable("order_lines");
echo "CREATED ORDERLINES: (" . count($createdOrderLines) . ")" ;
echo "<br>";
foreach ($createdOrderLines as $createdOrderLine)
{
    var_dump($createdOrderLine);
    echo "<br>";
}

$createdGenres = $crud->GetAllFromTable("genres");
echo "CREATED GENRES: (" . count($createdGenres) . ")" ;
echo "<br>";
foreach ($createdGenres as $createdGenre)
{
    var_dump($createdGenre);
    echo "<br>";
}

$createdArtists = $crud->GetAllFromTable("artists");
echo "CREATED ARTISTS: (" . count($createdArtists) . ")" ;
echo "<br>";
foreach ($createdArtists as $createdArtist)
{
    var_dump($createdArtist);
    echo "<br>";
}

$createdItemArtistsLinks = $crud->GetAllFromTable("item_artists");
echo "CREATED ARTISTSLINKS: (" . count($createdItemArtistsLinks) . ")" ;
echo "<br>";
foreach ($createdItemArtistsLinks as $createdItemArtistsLink)
{
    var_dump($createdItemArtistsLink);
    echo
     "<br>";
}
$createdItemGenresLinks = $crud->GetAllFromTable("item_genres");
echo "CREATED GERNRESLINKS: (" . count($createdItemGenresLinks) . ")" ;
echo "<br>";
foreach ($createdItemGenresLinks as $createdItemGenresLink)
{
    var_dump($createdItemGenresLink);
    echo "<br>";
}
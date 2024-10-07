<?php
include_once __DIR__.'/DatabaseConfig.php';
include_once __DIR__.'/DAOs/ItemDao.php';
include_once __DIR__.'/DAOs/OrderDao.php';
include_once __DIR__.'/DAOs/OrderLineDao.php';
include_once __DIR__.'/DAOs/UserDao.php';
include_once __DIR__.'/CRUD.php';

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
    ],
    'item_images' => [
                'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
                'item_id' => 'INT(6) UNSIGNED NOT NULL',   
                'image' => 'LONGBLOB NOT NULL',
                '' => 'FOREIGN KEY (item_id) REFERENCES items(id)',
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
        $adminUser = new User();
        $adminUser->setId(0);
        $adminUser->setName('Admin');
        $adminUser->setEmail('admin@example.com');
        $adminUser->setPassword('Admin123!');
        $adminUser->setDate_of_birth('1990-01-01');
        $adminUser->setGender('Male');
        $adminUser->setSearch_criteria('');
        $adminUser->setAdmin(True);

        $user1 = new User();
        $user1->setId(0);
        $user1->setName('test');
        $user1->setEmail('test@test.com');
        $user1->setPassword('Test123!');
        $user1->setDate_of_birth('1988-01-01');
        $user1->setGender('Male');
        $user1->setSearch_criteria('');
        $user1->setAdmin(False);

        $user2 = new User();
        $user2->setId(0);
        $user2->setName('Kroket');
        $user2->setEmail('Kroket@test.com');
        $user2->setPassword('Kroket123!');
        $user2->setDate_of_birth('1988-01-01');
        $user2->setGender('Male');
        $user2->setSearch_criteria('');
        $user2->setAdmin(False);

        $userDAO->Create($adminUser);
        $userDAO->Create($user1);
        $userDAO->Create($user2);
    }

    public function CreateMockItems()
    {
        $itemDAO = new ItemDAO();
        $dateAdded = date_create();
        $dateAdded = date_format($dateAdded, "Y-m-d");
        $images1 = [];
        array_push($images1, file_get_contents(__DIR__.'/../images/a_night_at_the_opera_front.jpg'));
        $images2 = [];
        array_push($images2, file_get_contents(__DIR__.'/../images/trench_front.jpg'));
        array_push($images2, file_get_contents(__DIR__.'/../images/trench_front2.jpg'));
        $images3 = [];
        array_push($images3, file_get_contents(__DIR__.'/../images/wish_you_were_here_front.jpg'));
        $images4 = [];
        array_push($images4, file_get_contents(__DIR__.'/../images/scaled_and_icy_front.jpg'));

        $item1 = new Item();
        $item1->setId(0);
        $item1->setTitle('A night at the opera');
        $item1->setArtists(['Queen']);
        $item1->setGenres(['rock']);
        $item1->setDescription("Queen's second studio album, released on 15 July 1975, was a significant breakthrough for the band. It marked the beginning of their post-punk and alternative rock career.");
        $item1->setStock(50);
        $item1->setYear(1975);
        $item1->setPrice(12.99);
        $item1->setType("CD");
        $item1->setDate_added($dateAdded);
        $item1->setImages($images1);
        
        $item2 = new Item();
        $item2->setId(0);
        $item2->setTitle('Trench');
        $item2->setArtists(['Twenty one pilots']);
        $item2->setGenres(['rock', 'pop']);
        $item2->setDescription("Great album");
        $item2->setStock(30);
        $item2->setYear(2018);
        $item2->setPrice(14.99);
        $item2->setType("VINYL");
        $item2->setDate_added($dateAdded);
        $item2->setImages($images2);

        $item3 = new Item();
        $item3->setId(0);
        $item3->setTitle('Wish you were here');
        $item3->setArtists(['Pink floyd']);
        $item3->setGenres(['rock classics']);
        $item3->setDescription('Fantastic album');
        $item3->setStock(25);
        $item3->setYear(1975);
        $item3->setPrice(14.99);
        $item3->setType("CD");
        $item3->setDate_added($dateAdded);
        $item3->setImages($images3);
        
        $item4 = new Item();
        $item4->setId(0);
        $item4->setTitle('Scaled and Icy');
        $item4->setArtists(['Twenty one pilots']);
        $item4->setGenres(['rock', 'pop']);
        $item4->setDescription("Great album");
        $item4->setStock(20);
        $item4->setYear(2022);
        $item4->setPrice(16.99);
        $item4->setType("VINYL");
        $item4->setDate_added($dateAdded);
        $item4->setImages($images4);

        $itemDAO->Create($item1);
        $itemDAO->Create($item2);
        $itemDAO->Create($item3);
        $itemDAO->Create($item4);
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
$result = $crud->GetAllFromTable("users");
$createdUsers = [];
while($row = $result->fetch_assoc())
{
    array_push($createdUsers, $row);
}
echo "CREATED USERS: (" . count($createdUsers) . ")" ;
echo "<br>";
foreach ($createdUsers as $createdUser)
{
    var_dump($createdUser);
    echo "<br>";
}

$result = $crud->GetAllFromTable("items");
$createdItems = [];
while($row = $result->fetch_assoc())
{
    array_push($createdItems, $row);
}
echo "CREATED ITEMS: (" . count($createdUsers) . ")" ;
echo "<br>";
foreach ($createdItems as $createdItem)
{
    var_dump($createdItem);
    echo "<br>";
}

$result = $crud->GetAllFromTable("orders");
$createdOrders = [];
while($row = $result->fetch_assoc())
{
    array_push($createdOrders, $row);
}
echo "CREATED ORDERS: (" . count($createdOrders) . ")" ;
echo "<br>";
foreach ($createdOrders as $createdOrder)
{
    var_dump($createdOrder);
    echo "<br>";
}

$result = $crud->GetAllFromTable("order_lines");
$createdOrderLines = [];
while($row = $result->fetch_assoc())
{
    array_push($createdOrderLines, $row);
}
echo "CREATED ORDERLINES: (" . count($createdOrderLines) . ")" ;
echo "<br>";
foreach ($createdOrderLines as $createdOrderLine)
{
    var_dump($createdOrderLine);
    echo "<br>";
}

$result = $crud->GetAllFromTable("genres");
$createdGenres = [];
while($row = $result->fetch_assoc())
{
    array_push($createdGenres, $row);
}
echo "CREATED GENRES: (" . count($createdGenres) . ")" ;
echo "<br>";
foreach ($createdGenres as $createdGenre)
{
    var_dump($createdGenre);
    echo "<br>";
}

$result = $crud->GetAllFromTable("artists");
$createdArtists = [];
while($row = $result->fetch_assoc())
{
    array_push($createdArtists, $row);
}
echo "CREATED ARTISTS: (" . count($createdArtists) . ")" ;
echo "<br>";
foreach ($createdArtists as $createdArtist)
{
    var_dump($createdArtist);
    echo "<br>";
}

$result = $crud->GetAllFromTable("item_artists");
$createdItemArtistsLinks = [];
while($row = $result->fetch_assoc())
{
    array_push($createdItemArtistsLinks, $row);
}
echo "CREATED ARTISTSLINKS: (" . count($createdItemArtistsLinks) . ")" ;
echo "<br>";
foreach ($createdItemArtistsLinks as $createdItemArtistsLink)
{
    var_dump($createdItemArtistsLink);
    echo
     "<br>";
}
$result = $crud->GetAllFromTable("item_genres");
$createdItemGenresLinks = [];
while($row = $result->fetch_assoc())
{
    array_push($createdItemGenresLinks, $row);
}
echo "CREATED GERNRESLINKS: (" . count($createdItemGenresLinks) . ")" ;
echo "<br>";
foreach ($createdItemGenresLinks as $createdItemGenresLink)
{
    var_dump($createdItemGenresLink);
    echo "<br>";
}

$result = $crud->GetAllFromTable("item_images");
$createdItemImages = [];
while($row = $result->fetch_assoc())
{
    array_push($createdItemImages, $row);
}
echo "CREATED ITEMIMAGES: (" . count($createdItemImages) . ")" ;
echo "<br>";
$images = [];
foreach ($createdItemImages as $createdItemImage)
{
    echo "image ID: " . $createdItemImage['id'] . "<br>";
    echo "item ID: " . $createdItemImage['item_id'] . "<br>";
    echo '<img src="data:image/jpeg;base64,'.base64_encode($createdItemImage['image']).'"/>';
}
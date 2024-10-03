<?php
include __DIR__.'/../DataTypes/User.php';
class UserDao
{
    private $CRUD;
    private $primaryColumn;
    public function __construct()
    {
        $this->CRUD = new CRUD();
        $this->primaryColumn = "id";
    }
    
    public function Create(User $user)
    {
        $userArray = [
            "id" => $user->getId(),
            "name" => $user->getName(),
            "email" => $user->getEmail(),
            "password" => $user->getPassword(),
            "date_of_birth" => $user->getDate_of_birth(),
            "gender" => $user->getGender(),
            "search_criteria" => $user->getSearch_criteria(),
            "admin" => $user->getAdmin()
        ];
        $result = $this->CRUD->Create("users", $userArray);
    }

    public function GetUsers()
    {
        $connection = new mysqli($this->host, $this->username, $this->password, $this->dbName);
        $query = "SELECT * FROM users";
        $result = $connection->query($query);
        return $result;
    }
    public function GetUserByEmail(string $email)
    {
        $sql = $this->CreateGetStatement("email", $email);
        $result = $this->ExecutePreparedStatement($sql, null);
        $row = $result->fetch_assoc();
        return $this->ConvertRowToDataType($row);
    }
    protected function ConvertRowToDataType($row)
    {
        $user = new User();
        $user->setId($row['id']);
        $user->setName($row['name']);
        $user->setEmail($row['email']);
        $user->setPassword($row['password']);
        $user->setDate_of_birth($row['date_of_birth']);
        $user->setGender($row['gender']);
        $user->setSearch_criteria($row['search_criteria']);
        $user->setAdmin($row['admin']);
    }
}
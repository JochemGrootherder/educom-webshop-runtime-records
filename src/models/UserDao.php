<?php
include_once 'DataTypes/User.php';
include_once 'CRUD.php';
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
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
            "password" => $user->password,
            "date_of_birth" => $user->date_of_birth,
            "gender" => $user->gender,
            "search_criteria" => $user->search_criteria,
            "admin" => $user->admin
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
        return new User(
            $row["id"],
            $row["name"],
            $row["email"],
            $row["password"],
            $row["date_of_birth"],
            $row["gender"],
            $row["search_criteria"],
            $row["admin"]
        );
    }
}
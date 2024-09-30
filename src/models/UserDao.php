<?php
include_once "BaseDAO.php";
class UserDao extends BaseDAO
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "users";
        $this->primaryColumn = "id";
        $this->dataType = "user";
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
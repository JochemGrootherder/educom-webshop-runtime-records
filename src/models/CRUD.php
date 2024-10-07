<?php
include_once __DIR__.'/DatabaseHandler.php';

class CRUD
{
    private $databaseHandler;
    private $LastInsertId;

    public function __construct()
    {
        $this->databaseHandler = DatabaseHandler::Connect();
    }

    public function Create(string $tableName, array $values)
    {
        $sql = $this->CreatePrepareInsertStatement($tableName, $values);
        $this->ExecutePreparedStatement($sql, $values);
    }

    public function Update(array $values)
    {
        $sql = $this->CreatePrepareUpdateStatement($values);
        $this->ExecutePreparedStatement($sql, $values);

    }

    public function Delete($identifier)
    {
        $sql = $this->CreatePrepareDeleteStatement($identifier);
        $this->ExecutePreparedStatement($sql, null);
    }

    public function Get(string $tableName, string $key, $identifier)
    {
        $sql = $this->CreatePrepareGetStatement($tableName, $key, $identifier);
        $result = $this->ExecutePreparedStatement($sql, null);
        if(!empty($result))
        {
            return $result;
        }
        return null;
    }

    
    private function CreatePrepareInsertStatement(string $tableName, array $values)
    {
        $keys = "";
        $keys .= implode(",", array_keys($values));

        $prepared_sql = "INSERT INTO " . $tableName . "(";
        $prepared_sql .= $keys;
        $prepared_sql .= ") VALUES (";
        $in = str_repeat('?, ', count($values)-1) . '?';
        $prepared_sql .= $in;
        $prepared_sql.= ")";
        return $prepared_sql;
    }

    private function CreatePrepareUpdateStatement(string $tableName, string $key, array $values)
    {
        $prepared_sql = "";
        $sets = "";
        foreach($values as $key => $value)
        {
            $sets.= $key. "= ?, ";
        }
        $sets = rtrim($sets, ", ");
        $prepared_sql.= "UPDATE ". $tableName. " SET ";
        $prepared_sql.= $sets;
        $prepared_sql.= " WHERE ". $key . "= '" . $values[$key] . "'";
        return $prepared_sql;
    }


    private function CreatePrepareDeleteStatement(string $tableName, string $key, $keyValue)
    {
        $sql = "DELETE FROM " . $tableName. " WHERE " . $key .  " = '" . $keyValue . "'";
        return $sql;
    }

    private function CreatePrepareGetStatement(string $tableName, string $key, $keyValue)
    {
        $sql = "SELECT * FROM " . $tableName . " WHERE " . $key . " = '" . $keyValue . "'";
        return $sql;
    }

    private function CreatePrepareGetStatementWhereAnd(string $tableName, $values)
    {
        $sql = "SELECT * FROM " . $tableName . " WHERE ";
        foreach($values as $key => $value)
        {
            $sql.= $key . " = ? AND ";
        } 
        $sql = rtrim($sql, "AND ");
        return $sql;
    }

    private function BindToStatement(&$statement, array $values)
    {
        $data = [];
        $bindIdentifiers = "";
        
        foreach($values as $key => $value)
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

    private function ExecutePreparedStatement($sql, ?array $values)
    {
        $connection = $this->databaseHandler->GetConnection();
        $statement = $connection->prepare($sql);
        if($values != null)
        {
            $this->BindToStatement($statement, $values);
        }
        $statement->execute();
        $result = $statement->get_result();
        $statement->close();
        $this->LastInsertId = $connection->insert_id;
        $connection->close();
        return $result;
    }

    public function GetAllFromTable(string $tableName)
    {
        $sql = "SELECT * FROM ". $tableName;
        $result = $this->ExecutePreparedStatement($sql, null);
        return $result;
    }

    public function GetFromTableWhereAnd(string $tableName, array $values)
    {
        $sql = $this->CreatePrepareGetStatementWhereAnd($tableName, $values);
        $result = $this->ExecutePreparedStatement($sql, $values);
        $rows = [];
        while($row = $result->fetch_assoc())
        {
            array_push($rows, $row);
        }
        return $rows;
    }

    public function GetLastInsertId()
    {
        return $this->LastInsertId;
    }
}
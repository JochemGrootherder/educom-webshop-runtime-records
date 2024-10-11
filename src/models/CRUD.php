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

    public function Update($tableName, $key, $values)
    {
        $sql = $this->CreatePrepareUpdateStatement($tableName, $key, $values);
        $this->ExecutePreparedStatement($sql, $values);

    }

    public function Delete($identifier)
    {
        $sql = $this->CreatePrepareDeleteStatement($identifier);
        $this->ExecutePreparedStatement($sql, null);
    }

    public function Get(string $tableName, string $key, $identifier)
    {
        $sql = $this->CreatePrepareGetStatement($tableName, $key);
        $values = [$identifier];
        $result = $this->ExecutePreparedStatement($sql, $values);
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

    private function CreatePrepareUpdateStatement(string $tableName, array $tableKeys, array $values)
    {
        $prepared_sql = "";
        $valueSets = "";
        $keySets = "";
        foreach($values as $key => $value)
        {
            $valueSets.= $key. "= ?, ";
        }
        $valueSets = rtrim($valueSets, ", ");

        foreach($tableKeys as $tableKey)
        {
            $keySets.= $tableKey. " = '" . $values[$tableKey]. "' AND ";
        }
        $keySets = rtrim($keySets, " AND ");

        $prepared_sql.= "UPDATE ". $tableName. " SET ";
        $prepared_sql.= $valueSets;
        $prepared_sql.= " WHERE ". $keySets;
        return $prepared_sql;
    }


    private function CreatePrepareDeleteStatement(string $tableName, string $key, $keyValue)
    {
        $sql = "DELETE FROM " . $tableName. " WHERE " . $key .  " = '" . $keyValue . "'";
        return $sql;
    }

    private function CreatePrepareGetStatement(string $tableName, string $key)
    {
        $sql = "SELECT * FROM " . $tableName . " WHERE " . $key . " = ?";
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
            $data[] = $value;
            $type = gettype($value);

            $bindIdentifier = 'b';
            if($type == 'boolean' || $type == 'integer') $bindIdentifier = 'i';
            if($type == 'string' ) $bindIdentifier = 's';
            if($type == 'double' || $type == 'float') $bindIdentifier = 'd';
            $bindIdentifiers .= $bindIdentifier;
         
         
         
            // switch($type)
            // {
            //     case "integer":
            //     case "boolean":
            //         $bindIdentifiers .= "i";
            //         break;
                    
            //     case "string":
            //         $bindIdentifiers .= "s";
            //         break;

            //     case "double":
            //     case "float":
            //         $bindIdentifiers .= "d";
            //         break;

            //     default: 
            //         $bindIdentifiers .= "b";
            //     break;
            // }
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
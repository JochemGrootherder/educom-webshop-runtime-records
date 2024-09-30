<?php
include_once "DataTypes.php";
include 'DatabaseHandler.php';

abstract class BaseDAO
{

    protected $tableName;
    protected $primaryColumn;
    protected $databaseHandler;
    protected $dataType;

    public function __construct()
    {
        $this->databaseHandler = DatabaseHandler::Connect();
    }

    public function Create(DataType $object)
    {
        $sql = $this->CreatePrepareInsertStatement($object);
        $this->ExecutePreparedStatement($sql, $object);
    }

    public function Update(DataType $object)
    {
        $sql = $this->CreatePrepareUpdateStatement($object);
        $this->ExecutePreparedStatement($sql, $object);

    }

    public function Delete($identifier)
    {
        $sql = $this->CreatePrepareDeleteStatement($identifier);
        $this->ExecutePreparedStatement($sql, null);
    }

    public function Get($identifier)
    {
        $sql = $this->CreatePrepareGetStatement($primaryColumn, $identifier);
        $result = $this->ExecutePreparedStatement($sql, null);
        $row = $result->fetch_assoc();
        return $this->ConvertRowToDataType($row, $this->dataType);
    }

    
    private function CreatePrepareInsertStatement(DataType $object)
    {
        $keys = "";
        $keys .= implode(",", array_keys((array)$object));

        $prepared_sql = "INSERT INTO " . $this->tableName . "(";
        $prepared_sql .= $keys;
        $prepared_sql .= ") VALUES (";
        $in = str_repeat('?, ', count((array)$object)-1) . '?';
        $prepared_sql .= $in;
        $prepared_sql.= ")";
        return $prepared_sql;
    }

    private function CreatePrepareUpdateStatement($object)
    {
        $prepared_sql = "";
        $sets = "";
        foreach($object as $key => $value)
        {
            $sets.= $key. "= ?, ";
        }
        $sets = rtrim($sets, ", ");
        $prepared_sql.= "UPDATE ". $this->tableName. " SET ";
        $prepared_sql.= $sets;
        $prepared_sql.= " WHERE ". $this->primaryColumn . "= '" . $object->$this->primaryColumn . "'";
        return $prepared_sql;
    }


    private function CreatePrepareDeleteStatement($keyValue)
    {
        $sql = "DELETE FROM " . $this-> tableName. " WHERE " . $this->primaryColumn . " = '" . $keyValue . "'";
        return $sql;
    }

    private function CreatePrepareGetStatement($key, $keyValue)
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE " . $key . " = '" . $keyValue . "'";
        return $sql;
    }
    private function BindToStatement(&$statement, DataType $object)
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
        $connection = $this->databaseHandler->GetConnection();
        $statement = $connection->prepare($sql);
        if($object != null)
        {
            $this->BindToStatement($statement, $object);
        }
        $statement->execute();
        $result = $statement->get_result();
        $statement->close();
        $connection->close();
        return $result;
    }

    public function GetAllFromTable()
    {
        $sql = "SELECT * FROM ". $this->tableName;
        $result = $this->ExecutePreparedStatement($sql, null);
        $results = [];
        while($row = $result->fetch_assoc())
        {
            $rowResult = $this->ConvertRowToDataType($row);
            array_push($results, $rowResult);
        }
        return $results;
    }
}
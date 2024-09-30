<?php
include_once "BaseDAO.php";
class ItemDao extends BaseDAO
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "items";
        $this->primaryColumn = "id";
        $this->dataType = "item";
    }

    protected function ConvertRowToDataType($row)
    {
        return new Item(
            $row["id"],
            $row["title"],
            $row["description"],
            $row["year"],
            $row["price"],
            $row["type"],
            $row["stock"],
            $row["date_added"]
        );
    }
}
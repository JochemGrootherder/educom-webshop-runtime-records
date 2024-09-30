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
}
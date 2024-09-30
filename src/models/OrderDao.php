<?php
include_once "BaseDAO.php";
class OrderDao extends BaseDAO
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "orders";
        $this->primaryColumn = "id";
        $this->dataType = "order";
    }
}
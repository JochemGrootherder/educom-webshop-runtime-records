<?php
include_once "BaseDAO.php";
class OrderLineDao extends BaseDAO
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "order_lines";
        $this->primaryColumn = "id";
        $this->dataType = "order_line";
    }
}
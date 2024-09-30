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

    protected function ConvertRowToDataType($row)
    {
        return new OrderLine(
            $row["id"],
            $row["order_id"],
            $row["item_id"],
            $row["amount"]
        );
    }
}
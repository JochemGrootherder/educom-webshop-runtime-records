<?php
include_once 'DataTypes/OrderLine.php';
include_once __DIR__.'/../CRUD.php';
class OrderLineDao
{
    private $CRUD;
    private $primaryColumn;
    public function __construct()
    {
        $this->CRUD = new CRUD();
        $this->primaryColumn = "id";
    }

    public function Create(OrderLine $OrderLine)
    {
        $OrderLineArray = [
            "id" => $OrderLine->getId(),
            "order_id" => $OrderLine->getOrder_id(),
            "item_id" => $OrderLine->getItem_id(),
            "amount" => $OrderLine->getAmount()
        ];

        $result = $this->$CRUD->Create("orders_lines", $OrderLineArray);
    }

    protected function ConvertRowToDataType($row)
    {
        $orderLine = new OrderLine();
        $orderLine->setId($row["id"]);
        $orderLine->setOrder_id($row["order_id"]);
        $orderLine->setItem_id($row["item_id"]);
        $orderLine->setAmount($row["amount"]);
        return $orderLine;
    }
}
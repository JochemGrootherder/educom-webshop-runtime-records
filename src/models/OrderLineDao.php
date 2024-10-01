<?php
include_once 'DataTypes/OrderLine.php';
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
            "id" => $OrderLine->id,
            "order_id" => $OrderLine->order_id,
            "item_id" => $OrderLine->item_id,
            "amount" => $OrderLine->amount
        ];

        $result = $this->$CRUD->Create("orders_lines", $OrderLineArray);
        var_dump($result);
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
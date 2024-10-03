<?php
include_once 'DataTypes/Order.php';
class OrderDao
{
    private $CRUD;
    private $primaryColumn;
    public function __construct()
    {
        $this->CRUD = new CRUD();
        $this->primaryColumn = "id";
    }

    public function Create(Order $Order)
    {
        $OrderArray = [
            "id" => $Order->getId(),
            "user_id" => $Order->getUser_id(),
            "date" => $Order->getDate()
        ];

        $result = $this->$CRUD->Create("orders", $OrderArray);
    }

    protected function ConvertRowToDataType($row)
    {
        $order = new Order();
        $order->setId($row["id"]);
        $order->setUser_id($row["user_id"]);
        $order->setDate($row["date"]);
        return $order;
    }
}
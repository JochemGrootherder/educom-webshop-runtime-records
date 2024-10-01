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
            "id" => $Order->id,
            "user_id" => $Order->user_id,
            "date" => $Order->date
        ];

        $result = $this->$CRUD->Create("orders", $OrderArray);
        var_dump($result);
    }

    protected function ConvertRowToDataType($row)
    {
        return new Order(
            $row["id"],
            $row["user_id"],
            $row["date"]
        );
    }
}
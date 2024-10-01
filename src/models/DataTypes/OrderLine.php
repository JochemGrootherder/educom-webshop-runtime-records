<?php

class OrderLine
{
    public function __construct(int $id, int $order_id, int $item_id, int $amount)
    {
        $this->id = $id;
        $this->order_id = $order_id;
        $this->item_id = $item_id;
        $this->amount = $amount;
    }

    public $id;
    public $order_id;
    public $item_id;
    public $amount;
}
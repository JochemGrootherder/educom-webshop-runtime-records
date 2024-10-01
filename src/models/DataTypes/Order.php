<?php

class Order
{
    public function __construct(int $id, int $user_id, string $date)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->date = $date;
    }

    public $id;
    public $user_id;
    public $date;

}
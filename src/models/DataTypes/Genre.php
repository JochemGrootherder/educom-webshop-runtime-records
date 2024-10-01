<?php

class Genre
{
    public function __construct(string $name, int $item_id)
    {
        $this->name = $name;
        $this->item_id = $item_id;
    }

    public $name;
    public $item_id;
}
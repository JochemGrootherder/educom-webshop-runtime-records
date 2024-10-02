<?php

class Genre
{
    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public $id = 0;
    public $name;
}
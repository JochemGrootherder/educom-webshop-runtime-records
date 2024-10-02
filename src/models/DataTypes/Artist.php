<?php

class Artist
{
    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public $id;
    public $name;
}
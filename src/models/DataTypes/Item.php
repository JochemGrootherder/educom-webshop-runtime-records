<?php

class Item
{
    public function __construct(int $id = 0
                            , string $title, array $artists
                            , array $genres, string $description
                            , int $year, float $price
                            , ItemTypes $type, int $stock, string $date_added)
    {
        $this->id = $id;
        $this->title = $title;
        $this->artists = $artists;
        $this->genres = $genres;
        $this->description = $description;
        $this->year = $year;
        $this->price = $price;
        $this->type = $type;
        $this->Stock = $stock;
        $this->date_added = $date_added;
    }
    public $id;
    public $title;
    public $artists;
    public $genres;
    public $description;
    public $year;
    public $price;
    public $type;
    public $stock;
    public $date_added;
}
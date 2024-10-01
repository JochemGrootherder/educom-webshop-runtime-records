<?php
include_once 'CRUD.php';
class ItemGenreDao
{
    private $CRUD;
    public function __construct()
    {
        $this->CRUD = new CRUD();
    }

    public function LinkItemAndGenres(int $itemId, array $genres)
    {
        foreach($genres as $genre)
        {   
            $itemGenreArray = ['item_id' => $itemId, 'genre_name' => $genre];
            $this->CRUD->Create("item_genres", $itemGenreArray);
        }
    }
}
<?php
include_once __DIR__.'/../CRUD.php';
class ItemGenreDao
{
    private $CRUD;
    public function __construct()
    {
        $this->CRUD = new CRUD();
    }

    public function LinkItemsAndGenres(int $itemId, int $genreId)
    {
        $itemGenresArray = ['item_id' => $itemId, 'genre_id' => $genreId];
        //check whether combination of artist and item already exists in linking tabel
        $result = $this->CRUD->GetFromTableWhereAnd("item_genres", $itemGenresArray);
        if(empty($result))
        {
            $this->CRUD->Create("item_genres", $itemGenresArray);
        }
    }
}
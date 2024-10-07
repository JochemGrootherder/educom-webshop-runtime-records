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

    public function GetGenresByItemId(int $itemId)
    {
        $result = $this->CRUD->Get("item_genres", "item_id", $itemId);
        if($result != null)
        {
            $genres = [];
            $genreDao = new GenreDao();
            while($row = $result->fetch_assoc())
            {
                $genre = $genreDao->GetGenreById($row['genre_id']);
                if($genre != null)
                {
                    array_push($genres, $genre);
                }
            }
            return $genres;
        }
        return null;
    }

    public function GetItemsByGenreId(int $genreId)
    {
        $result = $this->CRUD->Get("item_genres", "genre_id", $genreId);
        if($result != null)
        {
            $items = [];
            $itemDao = new ItemDao();
            while($row = $result->fetch_assoc())
            {
                $item = $itemDao->GetItemById($row['item_id']);
                if($item != null)
                {
                    array_push($items, $item);
                }
            }
            return $items;
        }
        return null;
    }
}
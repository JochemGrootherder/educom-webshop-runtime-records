<?php
include_once "CRUD.php";
include_once "DataTypes/Item.php";
include_once "DataTypes/ItemTypes.php";
include_once "ArtistDao.php";
include_once "GenreDao.php";
include_once "ItemArtistDao.php";
include_once "ItemGenreDao.php";

class ItemDao
{
    private $CRUD;
    private $primaryColumn;
    public function __construct()
    {
        $this->CRUD = new CRUD();
        $this->primaryColumn = "id";
    }

    private function ConvertRowToDataType($row)
    {
        return new Item(
            $row["id"],
            $row["title"],
            $row["description"],
            $row["year"],
            $row["price"],
            $row["type"],
            $row["stock"],
            $row["date_added"]
        );
    }

    public function Create(Item $item)
    {
        $itemArray = [
            "id" => $item->id,
            "title" => $item->title,
            "description" => $item->description,
            "year" => $item->year,
            "price" => $item->price,
            "type" => $item->type,
            "stock" => $item->stock,
            "date_added" => $item->date_added
        ];

        $this->CRUD->Create("items", $itemArray);
        $itemInsertId = $this->CRUD->GetLastInsertId();
        
        if(!empty($item->artists))
        {
            $artistDao = new ArtistDao();
            $itemArtist = new ItemArtistDao();
            foreach($item->artists as $artist)
            {
                $artistDao->Create(new Artist(0, $artist));
                $artistInsertId = $this->CRUD->GetLastInsertId();
                $itemArtist->LinkItemsAndArtists($itemInsertId, $artistInsertId);
            }
        }
        
        if(!empty($item->genres))
        {
            $genreDao = new GenreDao();
            $itemGenre = new ItemGenreDao();
            foreach($item->genres as $genre)
            {
                $genreDao->Create(new Genre(0, $genre));
                $genreInsertId = $this->CRUD->GetLastInsertId();
                $itemGenre->LinkItemsAndGenres($itemInsertId, $genreInsertId);
            }
        }
    }
}
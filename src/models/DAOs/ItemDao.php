<?php
include_once __DIR__."/../DataTypes/Item.php";
include_once __DIR__."/ArtistDao.php";
include_once __DIR__."/GenreDao.php";
include_once __DIR__."/ItemArtistDao.php";
include_once __DIR__."/ItemGenreDao.php";

class ItemDao
{
    private $CRUD;
    private $primaryColumn;
    public function __construct()
    {
        $this->CRUD = new CRUD();
        $this->primaryColumn = "id";
    }

    public function Create(Item $item)
    {
        $itemArray = [
            "id" => $item->getId(),
            "title" => $item->getTitle(),
            "description" => $item->getDescription(),
            "year" => $item->getYear(),
            "price" => $item->getPrice(),
            "type" => $item->getType(),
            "stock" => $item->getStock(),
            "date_added" => $item->getDate_added()
        ];

        $this->CRUD->Create("items", $itemArray);
        $itemInsertId = $this->CRUD->GetLastInsertId();
        
        if(!empty($item->getArtists()))
        {
            $artistDao = new ArtistDao();
            $itemArtist = new ItemArtistDao();
            foreach($item->getArtists() as $artist)
            {
                $newArtist = new Artist();
                $newArtist->setName($artist);
                $artistInsertId = $artistDao->Create($newArtist);
                echo "ArtistInsert ID: " . $artistInsertId;
                $itemArtist->LinkItemsAndArtists($itemInsertId, $artistInsertId);
            }
        }
        
        if(!empty($item->getGenres()))
        {
            $genreDao = new GenreDao();
            $itemGenre = new ItemGenreDao();
            foreach($item->getGenres() as $genre)
            {
                $newGenre = new Genre();
                $newGenre->setName($genre);
                $genreInsertId = $genreDao->Create($newGenre);
                //$this->CRUD->GetLastInsertId();
                echo "genreInsert ID: " . $genreInsertId;
                $itemGenre->LinkItemsAndGenres($itemInsertId, $genreInsertId);
            }
        }
    }
}
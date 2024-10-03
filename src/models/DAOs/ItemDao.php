<?php
include_once __DIR__."/../DataTypes/Item.php";
include_once __DIR__."/ArtistDao.php";
include_once __DIR__."/GenreDao.php";
include_once __DIR__."/ImageDao.php";
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

        $artists = $item->getArtists();
        if(!empty($artists))
        {
            $artistDao = new ArtistDao();
            $itemArtist = new ItemArtistDao();
            foreach($artists as $artist)
            {
                $newArtist = new Artist();
                $newArtist->setName($artist);
                $artistInsertId = $artistDao->Create($newArtist);
                $itemArtist->LinkItemsAndArtists($itemInsertId, $artistInsertId);
            }
        }
        
        $genres = $item->getGenres();
        if(!empty($genres))
        {
            $genreDao = new GenreDao();
            $itemGenre = new ItemGenreDao();
            foreach($genres as $genre)
            {
                $newGenre = new Genre();
                $newGenre->setName($genre);
                $genreInsertId = $genreDao->Create($newGenre);
                $itemGenre->LinkItemsAndGenres($itemInsertId, $genreInsertId);
            }
        }

        $images = $item->getImages();
        if(!empty($images))
        {  
            $imageDao = new ImageDao();
            foreach($images as $image)
            {
                $newImage = new Image();
                $newImage->setImage($image);
                $imageDao->Create($itemInsertId, $newImage);
            }

        }
    }
}
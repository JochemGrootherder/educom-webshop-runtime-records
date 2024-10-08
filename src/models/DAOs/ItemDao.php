<?php
include_once __DIR__."/../DataTypes/Item.php";
include_once __DIR__."/ArtistDao.php";
include_once __DIR__."/GenreDao.php";
include_once __DIR__."/ImageDao.php";
include_once __DIR__."/ItemArtistDao.php";
include_once __DIR__."/ItemGenreDao.php";
include_once __DIR__.'/../CRUD.php';

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
        $item = new Item();
        $item->setId($row['id']);
        $item->setTitle($row['title']);
        $item->setDescription($row['description']);
        $item->setYear($row['year']);
        $item->setPrice($row['price']);
        $item->setType($row['type']);
        $item->setStock($row['stock']);
        $item->setDate_added($row['date_added']);

        $itemArtistDao = new ItemArtistDao();
        $artists = $itemArtistDao->GetArtistsByItemId($item->GetId());
        $item->setArtists($artists);

        $itemGenreDao = new ItemGenreDao();
        $genres = $itemGenreDao->GetGenresByItemId($item->GetId());
        $item->setGenres($genres);

        $imageDao = new ImageDao();
        $images = $imageDao->GetImagesByItemId($item->GetId());
        $item->setImages($images);
        
        return $item;
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
    public function GetAllItems()
    {
        $result = $this->CRUD->GetAllFromTable('items');
        if($result != null)
        {
            $items = [];
            while($row = $result->fetch_assoc())
            {
                $item = $this->ConvertRowToDataType($row);
                array_push($items, $item);
            }
            return $items;
        }
        return null;
    }

    public function GetItemById(int $id)
    {
        $result = $this->CRUD->Get("items", "id", $id);
        if($result != null)
        {
            $row = $result->fetch_assoc();
            $item = $this->ConvertRowToDataType($row);
            return $item;
        }
        return null;
    }

}
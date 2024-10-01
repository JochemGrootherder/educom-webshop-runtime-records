<?php
include_once "CRUD.php";
include_once "DataTypes/Item.php";
include_once "DataTypes/ItemTypes.php";
include_once "ArtistDao.php";
include_once "GenreDao.php";

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

    public function Create(Item $Item)
    {
        $itemArray = [
            "id" => $Item->id,
            "title" => $Item->title,
            "description" => $Item->description,
            "year" => $Item->year,
            "price" => $Item->price,
            "type" => $Item->type,
            "stock" => $Item->stock,
            "date_added" => $Item->date_added
        ];

        $result = $this->$CRUD->Create("items", $itemArray);
        var_dump($result);
        
        if(!empty($Item->artists))
        {
            $artistDao = new ArtistDao();
            $artistDao->CreateFromArray($Item->artists);
        }
        
        if(!empty($Item->genres))
        {
            $genreDao = new GenreDao();
            $genreDao->CreateFromArray($Item->genres);
        }
    }
    //function create item
    //function link artist
    //function link genre
}
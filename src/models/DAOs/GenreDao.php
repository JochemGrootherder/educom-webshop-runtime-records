<?php
include_once __DIR__."/../DataTypes/Genre.php";
class GenreDao
{
    private $CRUD;
    private $primaryColumn;
    public function __construct()
    {
        $this->CRUD = new CRUD();
        $this->primaryColumn = "name";
    }

    private function ConvertRowToDataType($row)
    {
        $genre = new Genre();
        $genre->setId($row["id"]);
        $genre->setName($row["name"]);
        return $genre;
    }

    public function Create(Genre $genre)
    {
        $genreArray = [
            "id" => $genre->getId(),
            "name" => $genre->getName()
        ];

        $result = $this->CRUD->Get("genres", "name", $genreArray["name"]);
        if(empty($result))
        {
            $this->CRUD->Create("genres", $genreArray);
            //return the id it was inserted with, used for linking
            return $this->CRUD->GetLastInsertId(); 
        }
        //if the entry already exists return the id of the existing entry, used for linking
        return $result["id"];
    }
}
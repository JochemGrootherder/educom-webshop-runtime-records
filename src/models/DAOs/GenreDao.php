<?php
include_once __DIR__."/../DataTypes/Genre.php";
include_once __DIR__.'/../CRUD.php';
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
        if($result != null)
        {
            $row = $result->fetch_assoc();
            if(empty($row))
            {
                $this->CRUD->Create("genres", $genreArray);
                //return the id it was inserted with, used for linking
                return $this->CRUD->GetLastInsertId(); 
            }
            //if the entry already exists return the id of the existing entry, used for linking
            return $row["id"];
        }
        return null;
    }

    public function GetGenreById(int $id)
    {
        $result = $this->CRUD->Get("genres", "id", $id);
        if($result != null)
        {
            $row = $result->fetch_assoc();
            $genre = $this->ConvertRowToDataType($row);
            return $genre;
        }
    }
}
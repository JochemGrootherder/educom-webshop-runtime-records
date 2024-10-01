<?php
include_once "CRUD.php";
include_once "DataTypes/Genre.php";
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
        return new Genre(
            $row["name"]
        );
    }

    public function Create(Genre $genre)
    {
        $genreArray = [
            "name" => $genre->name
        ];

        $this->CRUD->Create("genres", $genreArray);
    }

    public function CreateFromArray(array $genres)
    {
        foreach($genres as $genre)
        {
            $genreArray = [
                "name" => $genre
            ];
            if(empty($this->CRUD->Get("genres", "name", $genre)))
            {
                $this->CRUD->Create("genres", $genreArray);
            }
        }
    }
}
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
        return new Genre(
            $row["name"]
        );
    }

    public function Create(Genre $genre)
    {
        $genreArray = [
            "id" => $genre->id,
            "name" => $genre->name
        ];

        $result = $this->CRUD->Get("genres", "name", $genreArray["name"]);
        if(empty($result))
        {
            $this->CRUD->Create("genres", $genreArray);
        }
    }
}
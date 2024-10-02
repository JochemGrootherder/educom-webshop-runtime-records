<?php
include_once __DIR__."/../DataTypes/Artist.php";
class ArtistDao
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
        return new Artist(
            $row["name"]
        );
    }

    public function Create(Artist $artist)
    {
        $artistArray = [
            "id" => $artist->id,
            "name" => $artist->name
        ];
        $result = $this->CRUD->Get("artists", "name", $artistArray["name"]);
        if(empty($result))
        {
            $this->CRUD->Create("artists", $artistArray);
        }
    }
}
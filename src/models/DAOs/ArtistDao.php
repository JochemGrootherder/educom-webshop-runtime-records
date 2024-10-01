<?php
include_once "CRUD.php";
include_once "DataTypes/Artist.php";
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
            "name" => $artist->name
        ];

        $CRUD->Create("artists", $artistArray);
    }

    public function CreateFromArray(array $artists)
    {
        foreach($artists as $artist)
        {
            $artistArray = [
                "name" => $artist->name
            ];

            $this->$CRUD->Create("artists", $artistArray);
        }
    }
}
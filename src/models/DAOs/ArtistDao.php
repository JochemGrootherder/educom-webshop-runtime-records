<?php
include_once __DIR__."/../DataTypes/Artist.php";
include_once __DIR__.'/../CRUD.php';
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
        $artist = new Artist();
        $artist->setId($row["id"]);
        $artist->setName($row["name"]);
        return $artist;
    }

    public function Create(Artist $artist)
    {
        $artistArray = [
            "id" => $artist->getId(),
            "name" => $artist->getName()
        ];

        $result = $this->CRUD->Get("artists", "name", $artistArray["name"]);
        if(empty($result))
        {
            $this->CRUD->Create("artists", $artistArray);
            //return the id it was inserted with, used for linking
            return $this->CRUD->GetLastInsertId();
        }
        //if the entry already exists return the id of the existing entry, used for linking
        return $result["id"];
    }
}
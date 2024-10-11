<?php
include_once __DIR__."/../DataTypes/Artist.php";
include_once __DIR__.'/../CRUD.php';
class ArtistDao
{
    private $CRUD;
    public function __construct()
    {
        $this->CRUD = new CRUD();
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
        if ($result != null)
        {
            $row = $result->fetch_assoc();
            if(empty($row))
            {
                $this->CRUD->Create("artists", $artistArray);
                //return the id it was inserted with, used for linking
                return $this->CRUD->GetLastInsertId();
            }
            //if the entry already exists return the id of the existing entry, used for linking
            return $row["id"];
        }
        return null;
    }

    public function GetArtistById(int $id)
    {
        $result = $this->CRUD->Get("artists", "id", $id);
        if($result != null)
        {
            $row = $result->fetch_assoc();
            $artist = $this->ConvertRowToDataType($row);
            return $artist;
        }
    }
}
<?php
include_once __DIR__."/../DataTypes/Image.php";
include_once __DIR__.'/../CRUD.php';
class ImageDao
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
        $image = new image();
        $image->setId($row["id"]);
        $image->setImage($row["image"]);
        return $image;
    }

    public function Create($itemId, Image $image)
    {
        $imageArray = [
            "id" => $image->getId(),
            "item_id" => $itemId,
            "image" => $image->getImage()
        ];

        $this->CRUD->Create("item_images", $imageArray);
        return $this->CRUD->GetLastInsertId();
    }

    public function GetImagesByItemId($itemId)
    {
        $images = [];
        $result = $this->CRUD->Get("item_images", "item_id", $itemId);
        while($row = $result->fetch_assoc())
        {
            $image = $this->ConvertRowToDataType($row);
            array_push($images, $image);
        }
        return $images;
    }
}
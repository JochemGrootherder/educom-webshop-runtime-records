<?php
include_once __DIR__.'/Page.php';
include_once __DIR__.'/../controllers/ItemController.php';

class ItemDetails extends Page
{
    public $ItemId;
    private $Item;

    public function __construct()
    {
    }

    public static function WithItemId($ItemId)
    {
        $instance = new self();
        $instance->ItemId = $ItemId;
        return $instance;
    }
    public function showTitle()
    {
        echo "Item Details";

    }
    public function showBody()
    {
        $this->GetItemDetails();
    }

    private function GetItemDetails()
    {
        $itemController = new ItemController();
        $this->Item = $itemController->GetItemById($this->ItemId);
    }
}
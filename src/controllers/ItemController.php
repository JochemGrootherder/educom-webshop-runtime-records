<?php
include_once __DIR__.'/../models/CRUD.php';
include_once __DIR__.'/../models/DAOs/ItemDao.php';
class ItemController
{
    public function __construct()
    {

    }

    public function GetAllItems()
    {
        $itemDao = new ItemDao();
        $items = $itemDao->GetAllItems();
        return $items;
    }
}
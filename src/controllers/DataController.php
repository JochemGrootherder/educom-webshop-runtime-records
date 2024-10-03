<?php
include_once __DIR__."/../models/CRUD.php";

class DataController
{
    public CRUD $CRUD;

    public function __construct()
    {
        $this->CRUD = new CRUD();
    }

    public function getAllItems()
    {
        $rows = $CRUD->GetAllFromTable("items");
        
    }

    public function getItemById($id)
    {

    }
}
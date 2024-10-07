<?php
include_once __DIR__.'/Page.php';
include_once __DIR__.'/../controllers/ItemController.php';

class Home extends Page
{
    function showTitle()
    {
        echo "HOME";

    }
    function showBody()
    {
        $itemController = new ItemController();
        $items = $itemController->getAllItems();
        foreach($items as $item)
        {
            echo 
            "id: " . $item->GetId() . "<br>" . 
            "Title: " . $item->GetTitle() . "<br>" . 
            "Description: " . $item->GetDescription() . "<br>" . 
            "Year: " . $item->GetYear() . "<br>" . 
            "Price: " . $item->GetPrice() . "<br>" . 
            "Type: " . $item->GetType() . "<br>" . 
            "Stock: " . $item->GetStock() . "<br>" . 
            "Date added: " . $item->GetDate_added() . "<br>"; 
            var_dump($item->GetArtists());
            echo "<br>";
            var_dump($item->GetGenres());
            echo "<br>";
            echo "<br>";
            echo "<br>";


            
        }
    }
}
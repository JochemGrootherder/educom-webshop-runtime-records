<?php
include_once 'CRUD.php';
class ItemArtistDao
{
    private $CRUD;
    public function __construct()
    {
        $this->CRUD = new CRUD();
    }

    public function LinkItemsAndArtists(int $itemId, array $artists)
    {
        foreach($artists as $artist)
        {   
            $itemArtistArray = ['item_id' => $itemId, 'artist_name' => $artist];
            $this->CRUD->Create("item_artists", $itemArtistArray);
        }
    }
}
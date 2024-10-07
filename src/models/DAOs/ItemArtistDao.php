<?php
include_once __DIR__.'/../CRUD.php';
class ItemArtistDao
{
    private $CRUD;
    public function __construct()
    {
        $this->CRUD = new CRUD();
    }

    public function LinkItemsAndArtists(int $itemId, int $artistId)
    {
        $itemArtistArray = ['item_id' => $itemId, 'artist_id' => $artistId];
        //check whether combination of artist and item already exists in linking tabel
        $result = $this->CRUD->GetFromTableWhereAnd("item_artists", $itemArtistArray);
        if(empty($result))
        {
            $this->CRUD->Create("item_artists", $itemArtistArray);
        }
    }
}
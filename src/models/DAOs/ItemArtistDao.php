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

    public function GetArtistsByItemId(int $itemId)
    {
        $result = $this->CRUD->Get("item_artists", "item_id", $itemId);
        if($result != null)
        {
            $artists = [];
            $artistDao = new ArtistDao();
            while($row = $result->fetch_assoc())
            {
                $artist = $artistDao->GetArtistById($row['artist_id']);
                if($artist!= null)
                {
                    array_push($artists, $artist);
                }
            }
            return $artists;
        }
        return null;
    }

    public function GetItemsByArtistId(int $artistId)
    {
        $result = $this->CRUD->Get("item_artists", "artist_id", $artistId);
        if($result != null)
        {
            $items = [];
            $itemDao = new ItemDao();
            while($row = $result->fetch_assoc())
            {
                $item = $itemDao->GetItemById($row['item_id']);
                if($item != null)
                {
                    array_push($items, $item);
                }
            }
            return $items;
        }
        return null;
    }

}
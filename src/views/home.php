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
        if(!empty($_SESSION['user_admin']) && $_SESSION['user_admin']==true)
        {
            $this->ShowAdminPanel();
        }
        $this->ShowItemCatalog();
    }

    private function GetContainerItems()
    {
        $containerItems = [];
        $itemController = new ItemController();
        $items = $itemController->GetAllItems();
        foreach($items as $item)
        {
            $id = $item->GetId();
            $images = $item->GetImages();
            $image = $images[0]->GetImage();
            
            $artistNames = [];
            foreach($item->GetArtists() as $artist)
            {
                array_push($artistNames, $artist->GetName());
            }
            $artistText = implode(", ", $artistNames);

            $title = $item->GetTitle();
            
            $price = $item->GetPrice();
            $prices = explode(".", $price, 2);
            $priceUpper = $prices[0];
            if(empty($prices[1]))
            {
                $prices[1] = '00';
            }
            $priceLower = $prices[1];
          
            array_push($containerItems, [
                'id'=> $id, 
                'image' => $image,
                'title' => $title, 
                'artists' => $artistText, 
                'priceUpper' => $priceUpper,
                'priceLower' => $priceLower]);
        }
        return $containerItems;
    }

    private function ShowItemCatalog()
    {
        echo "<div class='itemCatalog'>";
        foreach($this->GetContainerItems() as $item)
        {
            echo "
            <a href='?page=ItemDetails/".$item['id']."' class='itemContainer'>
                <div class='price-container'>
                    <div class='price-upper'>
                        " . $item['priceUpper'] . "
                    </div>
                        ." . $item['priceLower'] . "
                    <div class='price-lower'>
                    </div>
                </div>
                <div class='itemImageContainer'>
                    <img class= 'itemImage' src='data:image/jpeg;base64,".base64_encode($item['image'])."'/>
                </div>
                <div class='subtext'>
                    <div class='itemTitle'>"
                        . $item['title'] . "
                    </div>
                    <div class='itemArtists'> "
                        . $item['artists'] . " 
                    </div>
                </div>
            </a>
            ";   
        }
        echo "</div>";  
    }

    private function ShowAdminPanel()
    {
        echo '<a href="index.php?page=AddItem" class="menu-link">ADD ITEM</a>';
    }
}
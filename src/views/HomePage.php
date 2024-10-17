<?php
include_once __DIR__.'/Page.php';
include_once __DIR__.'/../controllers/ItemController.php';

class HomePage extends Page
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
            if($this->MatchesCriteria($item))
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
        }
        return $containerItems;
    }

    private function ShowItemCatalog()
    {
        echo "<div class='itemCatalog'>";
        foreach($this->GetContainerItems() as $item)
        {
            echo "
            <a href='?page=ItemDetailsPage/".$item['id']."' class='itemContainer'>
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
        echo '<a href="index.php?page=AddItemPage" class="menu-link">ADD ITEM</a>';
    }

    private function MatchesCriteria($item)
    {
        if(!empty($_SESSION['user_search_criteria']['Title']))
        {
            $criteriaTitle = strtolower($_SESSION['user_search_criteria']['Title']);
            $itemTitle = strtolower($item->GetTitle());
            if(!str_contains($criteriaTitle, $itemTitle) 
                && !str_contains($itemTitle, $criteriaTitle)) return false;
        }
        if(!empty($_SESSION['user_search_criteria']['Description']))
        {
            $criteriaDescription = strtolower($_SESSION['user_search_criteria']['Description']);
            $itemDescription = strtolower($item->GetDescription());
            if(!str_contains($criteriaDescription, $itemDescription) 
                && !str_contains($itemDescription, $criteriaDescription)) return false;
        }
        if(!empty($_SESSION['user_search_criteria']['Artists']))
        {
            $criteriaArtists = $_SESSION['user_search_criteria']['Artists'];
            $artists = $item->GetArtists();
            $contains = false;
            foreach($artists as $artist)
            {
                if(str_contains($criteriaArtists, $artist->GetName()))
                {
                    $contains = true;
                    break;
                }
            }
            if(!$contains) return false;
        }

        if(!empty($_SESSION['user_search_criteria']['Genres']))
        {
            $criteriaGenres = $_SESSION['user_search_criteria']['Genres'];
            $genres = $item->GetGenres();
            $contains = false;
            foreach($genres as $genre)
            {
                if(str_contains($criteriaGenres, $genre->GetName()))
                {
                    $contains = true;
                    break;
                } 
            }
            if(!$contains) return false;

        }
        if(!empty($_SESSION['user_search_criteria']['MinPrice']))
        {
            return $item->GetPrice() >= $_SESSION['user_search_criteria']['MinPrice'];

        }
        if(!empty($_SESSION['user_search_criteria']['MaxPrice']))
        {
            return $item->GetPrice() <= $_SESSION['user_search_criteria']['MaxPrice'];

        }
        if(!empty($_SESSION['user_search_criteria']['MinYear']))
        {
            return $item->GetYear() >= $_SESSION['user_search_criteria']['MinYear'];
        }
        if(!empty($_SESSION['user_search_criteria']['MaxYear']))
        {
            return $item->GetYear() <= $_SESSION['user_search_criteria']['MaxYear'];
        }
        if(!empty($_SESSION['user_search_criteria']['Type']))
        {
            $criteriaTypes = $_SESSION['user_search_criteria']['Type'];
            $itemType = $item->GetType();
            $contains = false;
            if(!str_contains($criteriaTypes, $itemType))
            {
                return false;
            }
        }
        return true;
    }
}
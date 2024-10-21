<?php
include_once __DIR__.'/FormPage.php';
include_once __DIR__.'/../controllers/ItemController.php';

class ItemDetailsPage extends FormPage
{
    private $Item;
    private $formResults;
    private $formData;

    public function __construct()
    {
        $this->formData = ADDTOCARTFORMDATA;
        $this->formResults = $this->CreateEmptyFormResults($this->formData);
    }

    public static function WithResults($ItemId, $formResults)
    {
        $instance = new self();
        $instance->GetItem($ItemId);
        $instance->formResults = $formResults;
        return $instance;
    }

    public static function WithItemId($ItemId)
    {
        $instance = new self();
        $instance->GetItem($ItemId);
        return $instance;
    }
    public function showTitle()
    {
        echo "Item Details";

    }
    public function showBody()
    {
        echo '
        <head>
            <link rel="stylesheet" href="./css/ItemDetailsPage.css">
        </head>';
        $this->ShowAddToCart();
        $this->ShowItemDetails();
    }

    private function GetItem($itemId)
    {
        $itemController = new ItemController();
        $this->Item = $itemController->GetItemById($itemId);
    }

    private function GetBodyElements()
    {
        $images = $this->Item->GetImages();
        $title = $this->Item->GetTitle();
        $artistNames = [];
        foreach($this->Item->GetArtists() as $artist)
        {
            array_push($artistNames, $artist->GetName());
        }
        $artistText = implode(", ", $artistNames);

        $genreNames = [];
        foreach($this->Item->GetGenres() as $genre)
        {
            array_push($genreNames, $genre->GetName());
        }
        $genreText = implode(", ", $genreNames);
        $description = $this->Item->GetDescription();
        $price = $this->Item->GetPrice();
        $prices = explode(".", $price, 2);
        $priceUpper = $prices[0];
        if(empty($prices[1]))
        {
            $prices[1] = '00';
        }
        $priceLower = $prices[1];
        $stock = $this->Item->GetStock();

        return [
            'images' => $images,
            'title' => $title,
            'artistText' => $artistText,
            'genreText' => $genreText,
            'description' => $description,
            'priceUpper' => $priceUpper,
            'priceLower' => $priceLower,
            'stock' => $stock
        ];
    }

    private function ShowItemDetails()
    {
        $elements = $this->GetBodyElements();
        echo "
        <h1>
        STOCK: ".$elements['stock']."
        </h1>
        <div class='item-details col-12'>
            <div class='row'>
                <div class='image-container col-lg-6 col-md-6 col-sm-12'>
                    <div class='price-container'>
                        <div class='price-upper'>
                            " . $elements['priceUpper'] . "
                        </div>
                        <div class='price-lower'>
                            ." . $elements['priceLower'] . "
                        </div>
                    </div>
                    <img class= 'itemImage' src='data:image/jpeg;base64,".base64_encode($elements['images'][0]->GetImage())."'/>
                </div>
                <div class='description-container col-lg-6 col-md-6 col-sm-12'>
                    <h2>" . $elements['title'] . "</h2>
                    <h3>" . $elements['artistText'] . "</h3>
                    <h4>" . $elements['genreText'] . "</h4>
                    <p>" . $elements['description'] . "</p>
                </div>
            </div>
        </div>";
    }

    private function ShowAddToCart()
    {
        $targetPage = 'AddToCartPage/' . $this->Item->GetId();
        $this->showForm($this->formData, $this->formResults, 'AddToCart', $targetPage, '', 'Add to cart');
    }
}
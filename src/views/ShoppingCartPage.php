<?php

class ShoppingCartPage extends Page
{    
    public function __construct()
    {

    }
    function showTitle()
    {
        echo "ShoppingCart";

    }
    function showBody()
    {
        $shoppingCartDao = new ShoppingCartDao();
        $shoppingCartItems = $shoppingCartDao->GetShoppingCartItems($_SESSION['user_id']);
        foreach($shoppingCartItems as $shoppingCartItem)
        {
            $amount = $shoppingCartItem['amount'];
            $item = $shoppingCartItem['item'];
            echo "Title: ". $item->GetTitle() . "<br>";
            echo "Price per piece: ". $item->GetPrice() . "<br>";
            echo "Amount: ". $amount. "<br>";
            echo "Total price: ". $item->GetPrice() * $amount . "<br>";
            echo "<br><br>";
        }
    }
}
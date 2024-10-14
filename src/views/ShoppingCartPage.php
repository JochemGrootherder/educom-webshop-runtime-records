<?php

class ShoppingCartPage extends Page
{    
    private $cartItems;
    public function __construct()
    {
        $this->cartItems = $this->GetItemsInCart();
    }

    public function showTitle()
    {
        echo "ShoppingCart";

    }

    public function showBody()
    {
        $this->showContent();
    }

    private function GetItemsInCart()
    {
        $shoppingCartDao = new ShoppingCartDao();
        return $shoppingCartDao->GetShoppingCartItems($_SESSION['user_id']);
    }

    private function ShowContent()
    {
        echo "
        <div class='cart-content'>
        <div class='shopping-cart-headers'>
            <h3 class='shopping-cart-remove'></h3>
            <h3 class='shopping-cart-header'>Title</h3>
            <h3 class='shopping-cart-header'>Price</h3>
            <h3 class='shopping-cart-header'>Amount</h3>
            <h3 class='shopping-cart-header'>Total price €</h3>
        </div>";

        foreach($this->cartItems as $cartItem)
        {
            $this->ShowCartItem($cartItem);
        }
        echo "
            <hr>
            <div class='total-price'>".$this->CalculateTotalPrice()."</div>
        </div>";

    }

    private function ShowCartItem($cartItem)
    {
        $amount = $cartItem['amount'];
        $item = $cartItem['item'];
        //<a href='index.php?page=RemoveItemPage/".$item->GetId()."' class='shopping-cart-remove-button'>Remove</a>
        echo "<div class='shopping-cart-elements'>
            <a href='index.php?page=RemoveItemPage/".$item->GetId()."' class='shopping-cart-element shopping-cart-remove'>Remove</a>
            <a href='index.php?page=ItemDetailsPage/".$item->GetId()."' class='shopping-cart-element'>" . $item->GetTitle() . "</a>
            <p class='shopping-cart-element'>" . $item->GetPrice() . "</p>
            <p class='shopping-cart-element'>" . $amount . "</p>
            <p class='shopping-cart-element'>" . $item->GetPrice() * $amount . "</p>
        </div>";
    }

    private function CalculateTotalPrice()
    {
        $totalPrice = 0.0;
        foreach($this->cartItems as $cartItem)
        {
            $totalPrice = $totalPrice + $cartItem['item']->GetPrice() * $cartItem['amount'];
        }
        return $totalPrice;
    }
}
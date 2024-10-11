<?php
include_once __DIR__.'/../CRUD.php';
class ShoppingCartItemDao
{
    private $CRUD;
    public function __construct()
    {
        $this->CRUD = new CRUD();
    }

    public function LinkShoppingCartAndItems(int $ShoppingCartId, int $itemId, int $amount)
    {
        $shoppingCartItemsArray = [
                                    'item_id' => $itemId, 
                                    'shopping_cart_id' => $ShoppingCartId];
        //check whether combination of item and shopping cart already exists in linking tabel
        $result = $this->CRUD->GetFromTableWhereAnd("shopping_cart_items", $shoppingCartItemsArray);
        if(empty($result))
        {
            $shoppingCartItemsArray['amount'] = $amount;
            $this->CRUD->Create("shopping_cart_items", $shoppingCartItemsArray);
        }
        else
        {
            $row = $result->fetch_assoc();
            $newAmount = $row['amount'] + $amount;
            $shoppingCartItemsArray['amount'] = $newAmount;
            $this->CRUD->Update("shopping_cart_items", ['shopping_cart_id', 'item_id'], $shoppingCartItemsArray);
        }
    }

    public function GetShoppingCartsByItemId(int $itemId)
    {
        $result = $this->CRUD->Get("shopping_cart_items", "item_id", $itemId);
        if($result != null)
        {
            $ShoppingCarts = [];
            $ShoppingCartDao = new ShoppingCartDao();
            while($row = $result->fetch_assoc())
            {
                $ShoppingCart = $ShoppingCartDao->GetShoppingCartById($row['shopping_cart_id']);
                if($ShoppingCart != null)
                {
                    array_push($ShoppingCarts, $ShoppingCart);
                }
            }
            return $ShoppingCarts;
        }
        return null;
    }

    public function GetItemsByShoppingCartId(int $ShoppingCartId)
    {
        $result = $this->CRUD->Get("shopping_cart_items", "Shopping_cart_id", $ShoppingCartId);
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
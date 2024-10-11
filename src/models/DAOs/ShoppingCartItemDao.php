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
            $newAmount = $result[0]['amount'] + $amount;
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

    public function GetItemsByShoppingCartId(int $shoppingCartId)
    {
        $result = $this->CRUD->Get("shopping_cart_items", "Shopping_cart_id", $shoppingCartId);
        if($result != null)
        {
            $items = [];
            $itemDao = new ItemDao();
            while($row = $result->fetch_assoc())
            {
                $item = $itemDao->GetItemById($row['item_id']);
                if($item != null)
                {
                    $amount = $this->GetItemCount($shoppingCartId, $row['item_id']);
                    $items[] = ['amount' => $amount, 'item' => $item];
                }
            }
            return $items;
        }
        return null;
    }

    private function GetItemCount($shoppingCartId, $itemId)
    {
        $selectArray = [
                    'shopping_cart_id' => $shoppingCartId,
                    'item_id' => $itemId];
        
        $result = $this->CRUD->GetFromTableWhereAnd("shopping_cart_items", $selectArray);
        if($result!= null)
        {
            return $result[0]['amount'];
        }
        return 0;
    }
}
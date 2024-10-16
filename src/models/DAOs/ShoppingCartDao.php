<?php
include_once __DIR__.'/../CRUD.php';
include_once __DIR__."/../DataTypes/ShoppingCart.php";
include_once __DIR__."/ShoppingCartItemDao.php";
class ShoppingCartDao
{
    private $CRUD;
    public function __construct()
    {
        $this->CRUD = new CRUD();
    }

    public function Create(ShoppingCart $shoppingCart)
    {
        $dateUpdated = date_create();
        $dateUpdated = date_format($dateUpdated, "Y-m-d-H-i-s");

        $shoppingCartArray = [
            "id" => $shoppingCart->getId(),
            "user_id" => $shoppingCart->getUserId(),
            "date_last_updated" => $dateUpdated
        ];

        $this->CRUD->Create("shopping_carts", $shoppingCartArray);
        $shoppingCart->SetId($this->CRUD->GetLastInsertId());
        return $shoppingCart;
    }

    public function AddToShoppingCart(int $shoppingCartId, int $ItemId, int $amount)
    {
        $shoppingCartItemsDao = new ShoppingCartItemDao();
        $shoppingCartItemsDao->LinkShoppingCartAndItems($shoppingCartId, $ItemId, $amount);
        $this->Update($shoppingCartId);
    }

    public function RemoveFromCart(int $shoppingCartId, int $itemId)
    {
        $shoppingCartItemsDao = new ShoppingCartItemDao();
        $shoppingCartItemsDao->Delete($shoppingCartId, $itemId);
        $this->Update($shoppingCartId);
    }

    public function GetShoppingCartItems($shoppingCartId)
    {
        if($shoppingCartId!= null)
        {
            $shoppingCartItemsDao = new ShoppingCartItemDao();
            return $shoppingCartItemsDao->GetItemsByShoppingCartId($shoppingCartId);
        }
    }

    private function Update($id)
    {
        $dateUpdated = date_create();
        $dateUpdated = date_format($dateUpdated, "Y-m-d-H-i-s");

        $whereValues = ['id' => $id];
        $setValues = ['date_last_updated' => $dateUpdated];
        $result = $this->CRUD->Update("shopping_carts", $setValues ,$whereValues);
    }

    public function Delete($id)
    {
        $this->EmptyShoppingCartByCartId($id);
        $whereValues = ['id' => $id];
        $this->CRUD->Delete("shopping_carts", $whereValues);
    }

    
    public function GetShoppingCartByUserId(int $userId)
    {
        $result = $this->CRUD->Get("shopping_carts", "user_id", $userId);
        if($result != null)
        {
            $row = $result->fetch_assoc();
            $shoppingCart = $this->ConvertRowToDataType($row);
            return $shoppingCart;
        }
    }

    public function CopyShoppingCart(int $originalId, int $newId)
    {
        $items = $this->GetShoppingCartItems($originalId);
        $shoppingCartItemsDao = new ShoppingCartItemDao();
        $shoppingCartItemsDao->CopyItemsByShoppingCartId($originalId, $newId);
        $this->Update($newId);
    }

    public function EmptyShoppingCartByCartId(int $cartId)
    {
        $whereValues = ['shopping_cart_id' => $cartId];
        $this->CRUD->Delete("shopping_cart_items", $whereValues);
    }

    public function GetAmountOfItem(int $shoppingCartId, int $itemId)
    {
        $values = [
            "shopping_cart_id" => $shoppingCartId,
            "item_id" => $itemId
        ];
        $result = $this->CRUD->GetFromTableWhereAnd("shopping_cart_items", $values);
        if($result != null)
        {
            return $result[0]["amount"];
        }
    }

    private function ConvertRowToDataType($row)
    {
        $shoppingCart = new ShoppingCart();
        $shoppingCart->setId($row["id"]);
        $shoppingCart->setUserId($row["user_id"]);
        $shoppingCart->SetDateLastUpdate($row["date_last_updated"]);
        return $shoppingCart;
    }
}
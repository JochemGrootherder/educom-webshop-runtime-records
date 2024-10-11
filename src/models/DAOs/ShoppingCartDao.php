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

        $result = $this->CRUD->Create("shopping_carts", $shoppingCartArray);
    }

    public function AddToShoppingCart($shoppingCartId, $ItemId, $amount)
    {
        $shoppingCartItemsDao = new ShoppingCartItemDao();
        $shoppingCartItemsDao->LinkShoppingCartAndItems($shoppingCartId, $ItemId, $amount);
        $this->Update($shoppingCartId);
    }

    public function GetShoppingCartItems($userId)
    {
        $shoppingCartItemsDao = new ShoppingCartItemDao();
        $shoppingCart = $this->GetShoppingCartByUserId($userId);
        if($shoppingCart!= null)
        {
            return $shoppingCartItemsDao->GetItemsByShoppingCartId($shoppingCart->getId());
        }
    }

    private function Update($id)
    {
        $dateUpdated = date_create();
        $dateUpdated = date_format($dateUpdated, "Y-m-d-H-i-s");

        $shoppingCartArray = [
            "id" => $id,
            "date_last_updated" => $dateUpdated
        ];

        $result = $this->CRUD->Update("shopping_carts", ['id'] ,$shoppingCartArray);
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

    private function ConvertRowToDataType($row)
    {
        $shoppingCart = new ShoppingCart();
        $shoppingCart->setId($row["id"]);
        $shoppingCart->setUserId($row["user_id"]);
        $shoppingCart->SetDateLastUpdate($row["date_last_updated"]);
        return $shoppingCart;
    }
}
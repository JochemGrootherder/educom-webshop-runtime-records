<?php
include_once __DIR__.'/../CRUD.php';
include_once __DIR__."/../DataTypes/ShoppingCart.php";
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
        var_dump($dateUpdated);

        $shoppingCartArray = [
            "id" => $shoppingCart->getId(),
            "user_id" => $shoppingCart->getUserId(),
            "date_last_updated" => $dateUpdated
        ];

        $result = $this->CRUD->Create("shopping_carts", $shoppingCartArray);
    }

    public function AddToShoppingCart($shoppingCartId, $ItemId, $amount)
    {
        $shoppingCartItemsDao = new ShoppingCartItemsDao();
        $shoppingCartItemsDao->LinkShoppingCartAndItems($shoppingCartId, $ItemId, $amount);
        $this->Update($shoppingCartId);
    }

    private function Update($id)
    {
        $dateUpdated = date_create();
        $dateUpdated = date_format($dateUpdated, "Y-m-d-H-i-s");

        $shoppingCartArray = [
            "id" => $shoppingCart->GetId(),
            "date_last_updated" => $dateUpdated
        ];

        $result = $this->$CRUD->Update("shopping_carts", ['id'] ,$shoppingCartArray);
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
        $shoppingCart->setDate($row["date_last_updated"]);
        return $shoppingCart;
    }
}
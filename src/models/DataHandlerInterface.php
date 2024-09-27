<?php
include 'DataTypes.php';
interface DataHandlerInterface
{
    function CreateUser(User $user);
    function UpdateUser(User $user);
    function DeleteUser(int $id);
    function GetUserById(int $id);
    function GetUserByEmail(string $email);

    function CreateItem(Item $item);
    function UpdateItem(Item $item);
    function DeleteItem(int $id);
    function GetItem(int $id);
    function GetItems();
    function GetItemsByFilter(string $filter);

    function CreateOrder(Order $order);
    function UpdateOrder(Order $order);
    function DeleteOrder(int $id);
    function GetOrder(int $id);

    function CreateOrderLine(OrderLine $orderLine);
    function UpdateOrderLine(OrderLine $orderLine);
    function DeleteOrderLine(int $id);
    function GetOrderLine(int $id);
}
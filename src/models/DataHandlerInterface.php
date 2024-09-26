<?php

interface DataHandlerInterface
{
    function CreateUser($user);
    function UpdateUser($user);
    function DeleteUser($id);
    function GetUserById($id);
    function GetUserByEmail($email);

    function CreateItem($user);
    function UpdateItem($user);
    function DeleteItem($id);
    function GetItem($id);
    function GetItems();
    function GetItemsByFilter($filter);

    function CreateOrder($order);
    function UpdateOrder($order);
    function DeleteOrder($id);
    function GetOrder($id);

    function CreateOrderLine($orderLine);
    function UpdateOrderLine($orderLine);
    function DeleteOrderLine($id);
    function GetOrderLine($id);
}
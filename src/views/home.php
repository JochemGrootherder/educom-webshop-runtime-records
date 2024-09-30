<?php
include_once 'FormPage.php';
include_once 'Models/DataTypes.php';
class Home extends Page
{
    function showTitle()
    {
        echo "HOME";

    }
    function showBody()
    {
        $date = date_create("1999-12-20");
        $date = date_format($date,"Y/m/d");
        $user = new User(0, "test", "test@test.com", "test", $date, "male", "", true);
        $database = DatabaseHandler::connect();
        //$database->CreateUser($user);
        $order = new Order(0,0,$date);
        $database->CreateOrder($order);

        $newOrder = new Order(0,1, $date);
        $database->UpdateOrder($newOrder);
    }


}
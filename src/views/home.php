<?php
include_once 'FormPage.php';
include_once 'Models/DataTypes.php';
include_once 'Models/UserDao.php';

class Home extends Page
{
    function showTitle()
    {
        echo "HOME";

    }
    function showBody()
    {
        $userDao = new UserDao();
        $users = $userDao->getAllfromTable();
        foreach ($users as $user) {
            var_dump($user);
            echo "<br><br>";
        }
    }


}
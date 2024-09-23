
<?php

function updateAllowedPages()
{
    if(empty($_SESSION['user']))
    {
        $_SESSION['allowedPages'] = array('home.php', 'itemDetails.php', 'shoppingCart.php', 'register.php', 'login.php');
    }
    elseif($_SESSION['user']['admin'] === true)
    {
        $_SESSION['allowedPages'] = array('home.php', 'itemDetails.php', 'orders.php', 'profile.php', 'shoppingCart.php', 'logout.php', 'editItem.php');
    }
    else
    {
        $_SESSION['allowedPages'] = array('home.php', 'itemDetails.php', 'orders.php', 'profile.php', 'shoppingCart.php', 'logout.php');
    }
}

?>
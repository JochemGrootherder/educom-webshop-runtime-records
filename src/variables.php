
<?php

function updateAllowedPages()
{
    if(empty($_SESSION['user']))
    {
        $_SESSION['allowedPages'] = array('Home', 'ItemDetails', 'ShoppingCart', 'Register', 'Login');
    }
    elseif($_SESSION['user']['admin'] === true)
    {
        $_SESSION['allowedPages'] = array('Home', 'ItemDetails', 'Orders', 'Profile', 'ShoppingCart', 'Logout', 'EditItem');
    }
    else
    {
        $_SESSION['allowedPages'] = array('Home', 'ItemDetails', 'Orders', 'Profile', 'ShoppingCart', 'Logout');
    }
}

?>
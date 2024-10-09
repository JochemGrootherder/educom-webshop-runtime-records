
<?php

function updateAllowedPages()
{
    if(empty($_SESSION['user_name']))
    {
        $_SESSION['allowedPages'] = array('Home', 'ItemDetails', 'ShoppingCart', 'Register', 'Login');
    }
    elseif($_SESSION['user_admin'] === true)
    {
        $_SESSION['allowedPages'] = array('Home', 'ItemDetails', 'Orders', 'Profile', 'ShoppingCart', 'Logout', 'EditItem', 'AddItem');
    }
    else
    {
        $_SESSION['allowedPages'] = array('Home', 'ItemDetails', 'Orders', 'Profile', 'ShoppingCart', 'Logout');
    }
}

?>
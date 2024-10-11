
<?php

function updateAllowedPages()
{
    if(empty($_SESSION['user_name']))
    {
        $_SESSION['allowedPages'] = array('HomePage', 'ItemDetailsPage', 'ShoppingCartPage', 'AddToCartPage', 'RegisterPage', 'LoginPage');
    }
    elseif($_SESSION['user_admin'] === true)
    {
        $_SESSION['allowedPages'] = array('HomePage', 'ItemDetailsPage', 'OrdersPage', 'ProfilePage', 'ShoppingCartPage', 'AddToCartPage', 'LogoutPage', 'EditItemPage', 'AddItemPage');
    }
    else
    {
        $_SESSION['allowedPages'] = array('HomePage', 'ItemDetailsPage', 'OrdersPage', 'ProfilePage', 'ShoppingCartPage', 'AddToCartPage', 'LogoutPage');
    }
}

?>

<?php


function UpdateVariables()
{
    updateAllowedPages();
}

function updateAllowedPages()
{
    $_SESSION['allowedPages'] = ['HomePage', 'ItemDetailsPage', 'ShoppingCartPage', 'AddToCartPage', 'RemoveFromCartPage'];
    if(empty($_SESSION['user_name']))
    {
        $_SESSION['allowedPages'][] = 'RegisterPage';
        $_SESSION['allowedPages'][] = 'LoginPage';
        return;
    }
    
    $_SESSION['allowedPages'][] = 'OrdersPage';
    $_SESSION['allowedPages'][] = 'ProfilePage';
    $_SESSION['allowedPages'][] = 'LogoutPage';
    
    if($_SESSION['user_admin'] === true)
    {
        $_SESSION['allowedPages'][] = 'EditItemPage';
        $_SESSION['allowedPages'][] = 'AddItemPage';
    }
}

?>
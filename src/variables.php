
<?php


function UpdateVariables()
{
    $shoppingCartDao = new ShoppingCartDao();
    if(empty($_SESSION['shopping_cart_id']))
    {
        $shoppingCartId = $shoppingCartDao->Create(new ShoppingCart());
        $_SESSION['shopping_cart_id'] = $shoppingCartId;
    }
    updateAllowedPages();
}

function updateAllowedPages()
{
    $_SESSION['allowedPages'] = ['HomePage', 'ItemDetailsPage', 'ShoppingCartPage', 'AddToCartPage'];
    if(empty($_SESSION['user_name']))
    {
        $_SESSION['allowedPages'][] = 'RegisterPage';
        $_SESSION['allowedPages'][] = 'LoginPage';
        return;
    }
    
    $_SESSION['allowedPages'][] = 'OrdersPage';
    $_SESSION['allowedPages'][] = 'ProfilePage';
    $_SESSION['allowedPages'][] = 'LogoutPage';
    $_SESSION['allowedPages'][] = 'RemoveFromCartPage';
    
    if($_SESSION['user_admin'] === true)
    {
        $_SESSION['allowedPages'][] = 'EditItemPage';
        $_SESSION['allowedPages'][] = 'AddItemPage';
    }
}

?>
<?php
abstract class Page
{
    public function showHeadSection()
    {
        echo '<head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>My Webshop</title>
        
            <link rel="stylesheet" href="./CSS/stylesheet.css">
        </head>';
    }
    
    public function showHeader()
    {
        echo '<header> <h1>';
        echo $this->showTitle();
        echo '</h1>';
        $this->showMenu();
        echo '</header>';
    }
    
    public function showMenu()
    {
        echo '
            <ul class="nav-menu">
            <li class="nav-menu-item">
                <a href="index.php?page=Home" class="menu-link">HOME</a>
            </li>
            <li class="nav-menu-item">
                <a href="index.php?page=ShoppingCart" class="menu-link">SHOPPING CART</a>
            </li>
            ';
            if(empty($_SESSION['user_name'])){
                echo'
            <li class="nav-menu-item">
                <a href="index.php?page=Login" class="menu-link">LOGIN</a>
            </li>
            <li class="nav-menu-item">
                <a href="index.php?page=Register" class="menu-link">REGISTER</a>
            </li>';
            }else
            {
                echo'
                <li class="nav-menu-item">
                    <a href="index.php?page=Profile" class="menu-link">PROFILE</a>
                </li>
                <li class="nav-menu-item">
                    <a href="index.php?page=Logout" class="menu-link">LOG OUT</a>
                </li>';
            }
        echo '</ul>';
    }
    
    public function showFooter()
    {
        echo '<footer> &copy 2024 Jochem Grootherder </footer>';
    }
    function showBodySection()
    {
        echo '<body>';
        $this->showHeader();
        $this->showBody();
        $this->showFooter();
    
        echo '</body>';
    }
    
    public abstract function ShowTitle();
    public abstract function ShowBody();
}
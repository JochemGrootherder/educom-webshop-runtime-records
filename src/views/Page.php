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
        
            <link rel="stylesheet" href="./css/Style.css">
            <link rel="stylesheet" href="css/bootstrap.min.css" />
        </head>';
    }
    
    public function showHeader()
    {
        echo'
        <header>
        <a class="logo" href="index.php?page=HomePage">
            <img src="Images/logo.png" class="header-logo">
            <h1 class="header-title">runtime-records</h1>
        </a>
        <div class="dropdown">
            <button onclick="myFunction()" class="dropbtn"></button>
            <div id="myDropdown" class="dropdown-content">
                <a href="index.php?page=HomePage">HOME</a>
                <a href="index.php?page=ShoppingCartPage">SHOPPING CART</a>
            ';
            if(empty($_SESSION['user_name'])){
                echo'
                <a href="index.php?page=LoginPage">LOGIN</a>
                <a href="index.php?page=RegisterPage">REGISTER</a>';
            }else
            {
                echo'
                    <a href="index.php?page=ProfilePage">PROFILE</a>
                    <a href="index.php?page=LogoutPage">LOG OUT</a>';
            }
            echo '
            </div>
        </div>
        ';
        include_once __DIR__.'/../scripts/DropDownScript.php';
        echo '</header>';
    }
    
    public function showFooter()
    {
        echo '
        <footer>
        <div class="footer-container col-md-12">
            <div class="row">
                <div class="col-3">
                    <h3>runtime records</h3>
                    <ul class="contact-info-list">
                        <li class="contact-method">
                            <div class="contact-method-logo">
                            </div>
                            <div class="contact-method-logo">
                                <p>info@runtime-records.com</p>
                            </div>
                        </li>
                        <li class="contact-method">
                            <div class="contact-method-logo">
                            </div>
                            <div class="contact-method-logo">
                                <p>disogs.com/runtime-records</p>
                            </div>
                        </li>
                        <li class="contact-method">
                            <div class="contact-method-logo">
                            </div>
                            <div class="contact-method-logo">
                                <p>facebook.com/runtime-records</p>
                            </div>
                        </li>
                        <li class="contact-method">
                            <div class="contact-method-logo">
                            </div>
                            <div class="contact-method-logo">
                                <p>@RuntimeRecords</p>
                            </div>
                        </li>
                        <li class="contact-method">
                            <div class="contact-method-logo">
                            </div>
                            <div class="contact-method-logo">
                                <p>@RuntimeRecords</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-6">
                    <h3>info</h3>
                    <ul class="info-list">
                        <li>
                        <p>About us</p>
                        </li>
                        <li>
                        <p>Privacy Policy</p>
                        </li>
                        <li>
                        <p>Shipping</p>
                        </li>
                    </ul>
                </div>
                <div class="col-3 footer-images">
                <img src="Images/record-store-day.png" class="footer-image">
                <img src="Images/logo.png" class="footer-image" style="filter: invert(1)">
                </div>
            </div>
        </div>
        </footer>';
    }
    function showBodySection()
    {
        echo '<body>';
        $this->showHeadSection();
        $this->showHeader();
        $this->showBody();
        $this->showFooter();
        echo '<script src="js/bootstrap.min.js"></script>';
    
        echo '
        </body>';
    }
    
    public abstract function ShowTitle();
    public abstract function ShowBody();
}
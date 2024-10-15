<?php
include 'DataExtractor.php';
include 'InputValidator.php';
include_once "models/DAOs/UserDao.php";
include_once "models/DAOs/ShoppingCartDao.php";

//include all files in pages directory
foreach (glob("views/*.php") as $filename)
{
    include_once $filename;
}

Class PageController
{
    private $dataExtractor = NULL;
    private $inputValidator = NULL;
    private $currentPage = NULL;

    public function __construct()
    {
        $this->dataExtractor = new DataExtractor();
        $this->inputValidator = new InputValidator($this->dataExtractor);
    }

    public function ExecuteAction()
    {
        $requestedPage = $this->GetRequestedPage();
        $this->HandlePageRequest($requestedPage);
        $this->ShowPage();
    }

    private function ShowPage()
    {
        $this->currentPage->showBodySection();
    }

    private function GetRequestedPage()
    {
        $requestedType = $_SERVER['REQUEST_METHOD'];
        if($requestedType == 'POST')
        {
            $requestedPage = $this->dataExtractor->getPostVar('page', 'Home');
        }
        else // Method is GET
        {
            $requestedPage = $this->dataExtractor->getUrlVar('page', 'Home');
        }
        return $requestedPage;
    }

    private function HandlePageRequest($page)
    {
        $pageRequest = explode('/', $page, 2);
        $pageName = $pageRequest[0];
        if(!in_array($pageName, $_SESSION['allowedPages']))
        {
            echo '<script>alert("Invalid page requested, redirecting to homepage");</script>';
            $this->currentPage = new HomePage();
            return false;
        }
        switch($pageName)
        {
            case 'RegisterPage':
                $this->HandleRegisterRequest();
                break;
            case 'LoginPage':
                $this->HandleLoginRequest();
                break;
            case 'LogoutPage':
                $this->HandleLogoutRequest();
                break;
            case 'ItemDetailsPage':
                $this->HandleItemDetailsRequest($pageRequest[1]);
                break;
            case 'AddItemPage':
                $this->HandleAddItemRequest();
                break;
            case 'AddToCartPage':
                $this->HandleAddToCartRequest($pageRequest[1]);
                break;
            case 'RemoveFromCartPage':
                $this->HandleRemoveFromCartRequest($pageRequest[1]);
                break;
            default: 
                $this->currentPage = new $pageName();
                break;
        }
        return $pageName;
    }
    private function containsErrors($formResults)
    {
        $containsErrors = false;
        foreach($formResults as $key => $formResult)
        {
            if(!empty($formResult['error']))

            {
                return true;
            }
        }
        return $containsErrors;
    }

    private function HandleRegisterRequest()
    {
        if($this->dataExtractor->getPostVar('formDataName') === 'Register')
        {
            $validatedInput = $this->inputValidator->validateInput(REGISTERFORMDATA);
            if(!$this->containsErrors($validatedInput))
            {
                include_once __DIR__."/../models/DataTypes/User.php";
                $user = new User();
                $user->SetName($this->dataExtractor->getPostVar('Name'));
                $user->SetEmail($this->dataExtractor->getPostVar('Email'));
                $user->SetPassword($this->dataExtractor->getPostVar('Password'));
                $user->SetDate_of_birth($this->dataExtractor->getPostVar('DateOfBirth'));
                $user->SetGender($this->dataExtractor->getPostVar('Gender'));

                $userDao = new UserDao();
                $userDao->Create($user);
                header("Location: index.php?page=LoginPage");
                //$this->currentPage = new LoginPage();
            }
            else
            {
                $this->currentPage = RegisterPage::WithResults($validatedInput);
            }
        }
        else
        {
            $this->currentPage = new RegisterPage();
        }
    }

    private function HandleLoginRequest()
    {
        if($this->dataExtractor->getPostVar('formDataName') === 'Login')
        {
            $validatedInput = $this->inputValidator->validateInput(LOGINFORMDATA);
            if(!$this->containsErrors($validatedInput))
            {
                $email = $this->dataExtractor->GetPostVar('Email');
                $userDao = new UserDao();
                $shoppingCartDao = new ShoppingCartDao();
                $user = $userDao->GetUserByEmail($email);
                $_SESSION['user_id'] = $user->GetId();
                $_SESSION['user_name'] = $user->GetName();
                $_SESSION['user_email'] = $user->GetEmail();
                $_SESSION['user_search_criteria'] = $user->GetSearch_criteria();
                $_SESSION['user_admin'] = $user->GetAdmin();

                $shoppingCart = $shoppingCartDao->GetShoppingCartByUserId($_SESSION['user_id']);
                $shoppingCartDao->CopyShoppingCart($_SESSION['shopping_cart_id'], $shoppingCart->GetId());
                $shoppingCartDao->Delete($_SESSION['shopping_cart_id']);
                $_SESSION['shopping_cart_id'] = $shoppingCart->GetId();

                updateAllowedPages();
                header("Location: index.php?page=HomePage");
                // $this->currentPage = new HomePage();
            }
            else
            {
                $this->currentPage = LoginPage::WithResults($validatedInput);
            }
        }
        else
        {
            $this->currentPage = new LoginPage();
        }
    }

    private function HandleLogoutRequest()
    {
        $this->UnsetShoppingCart();
        session_unset();
        updateAllowedPages();
        header("Location: index.php?page=HomePage");
        //$this->currentPage = new HomePage();
    }

    private function HandleItemDetailsRequest($id)
    {
        $this->currentPage = ItemDetailsPage::WithItemId($id);
    }

    private function HandleAddItemRequest()
    {
        if($this->dataExtractor->getPostVar('formDataName') === 'AddItem')
        {
            $validatedInput = $this->inputValidator->validateInput(ADDITEMFORMDATA);
            if(!$this->containsErrors($validatedInput))
            {
                $item = new Item();
                $item->SetTitle($validatedInput['Title']['value']);
                $item->SetDescription($validatedInput['Description']['value']);
                $item->SetPrice($validatedInput['Price']['value']);
                $item->SetArtists([$validatedInput['Artists']['value']]);
                $item->SetGenres([$validatedInput['Genres']['value']]);
                $item->SetYear($validatedInput['Year']['value']);
                $item->SetStock($validatedInput['Stock']['value']);
                $image = file_get_contents($_FILES['ImagesToUpload']['tmp_name']);
                $item->SetImages([$image]);
                $dateAdded = date_create();
                $dateAdded = date_format($dateAdded, "Y-m-d");
                $item->SetDate_added($dateAdded);
                
                $itemDao = new ItemDao();
                $itemDao->Create($item);
                header("Location: index.php?page=HomePage");
                
                //$this->currentPage = new HomePage();
            }
            else
            {
                $this->currentPage = AddItemPage::WithResults(ADDITEMFORMDATA, $validatedInput);
            }
        }
        else
        {
            $this->currentPage = new AddItemPage();
        }
    }

    private function HandleAddToCartRequest($itemId)
    {
        if($this->dataExtractor->getPostVar('formDataName') === 'AddToCart')
        {

            $validatedInput = $this->inputValidator->validateInput(ADDTOCARTFORMDATA);
            if(!$this->containsErrors($validatedInput))
            {
                $shoppingCartDao = new ShoppingCartDao();
                $amount = $validatedInput['Amount']['value'];
                $shoppingCartDao->AddToShoppingCart($_SESSION['shopping_cart_id'], $itemId, $amount);

                $itemDao = new ItemDao();
                $itemDao->DecreaseItemStock($itemId, $amount);

                //$this->currentPage = new ShoppingCartPage();
                header("Location: index.php?page=ShoppingCartPage");
            }
            else
            {
                $this->currentPage = ItemDetailsPage::WithResults($itemId, $validatedInput);
            }
        }
        else
        {
            $this->currentPage = new HomePage();
        }
    }

    private function HandleRemoveFromCartRequest($itemId)
    {
        $shoppingCartDao = new ShoppingCartDao();
        $shoppingCart = $shoppingCartDao->GetShoppingCartByUserId($_SESSION['user_id']);
        $shoppingCartId = $shoppingCart->GetId();

        $amount = $shoppingCartDao->GetAmountOfItem($shoppingCartId, $itemId);
        
        $shoppingCartDao->RemoveFromCart($shoppingCartId, $itemId);
        //Remove from cart

        if($amount != 0)
        {
            $itemDao = new ItemDao();
            $itemDao->IncreaseItemStock($itemId, $amount);
        }
        header("Location: index.php?page=ShoppingCartPage");
        //$this->currentPage = new ShoppingCartpage();
    }

    private function UnsetShoppingCart()
    {
        //clear shopping cart
        $shoppingCartDao = new ShoppingCartDao();
        $itemDao = new ItemDao();
        $itemsInCart = $shoppingCartDao->GetShoppingCartItems($_SESSION['shopping_cart_id']);
        foreach($itemsInCart as $itemInCart)
        {
            $amount = $itemInCart['amount'];
            $itemId = $itemInCart['item']->GetId();
            $itemDao->IncreaseItemStock($itemId, $amount);
        }
        $shoppingCartDao->EmptyShoppingCartByCartId($_SESSION['shopping_cart_id']);

    }

}
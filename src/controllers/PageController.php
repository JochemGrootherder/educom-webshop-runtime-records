<?php
include 'DataExtractor.php';
include 'InputValidator.php';
include_once "models/DAOs/UserDao.php";

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
            $this->currentPage = new Home();
            return false;
        }
        switch($pageName)
        {
            case 'Register':
                $this->HandleRegisterRequest();
                break;
            case 'Login':
                $this->HandleLoginRequest();
                break;
            case 'Logout':
                $this->HandleLogoutRequest();
                break;
            case 'ItemDetails':
                $this->HandleItemDetailsRequest($pageRequest[1]);
                break;
            case 'AddItem':
                $this->HandleAddItemRequest();
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
                $this->currentPage = new Login();
            }
            else
            {
                $this->currentPage = Register::WithResults($validatedInput);
            }
        }
        else
        {
            $this->currentPage = new Register();
        }
    }

    private function HandleLoginRequest()
    {
        if($this->dataExtractor->getPostVar('formDataName') === 'Login')
        {
            $validatedInput = $this->inputValidator->validateInput(LOGINFORMDATA);
            if(!$this->containsErrors($validatedInput))
            {
                $email = $this->dataExtractor->getPostVar('Email');
                $userDao = new UserDao();
                $user = $userDao->GetUserByEmail($email);
                $_SESSION['user_name'] = $user->getName();
                $_SESSION['user_email'] = $user->getEmail();
                $_SESSION['user_search_criteria'] = $user->GetSearch_criteria();
                $_SESSION['user_admin'] = $user->GetAdmin();
                updateAllowedPages();
                $this->currentPage = new Home();
            }
            else
            {
                $this->currentPage = Login::WithResults($validatedInput);
            }
        }
        else
        {
            $this->currentPage = new Login();
        }
    }

    private function HandleLogoutRequest()
    {
        session_unset();
        updateAllowedPages();
        $this->currentPage = new Home();
    }

    private function HandleItemDetailsRequest($id)
    {
        $this->currentPage = ItemDetails::WithItemId($id);
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
                
                $this->currentPage = new Home();
            }
            else
            {
                $this->currentPage = AddItem::WithResults(ADDITEMFORMDATA, $validatedInput);
            }
        }
        else
        {
            $this->currentPage = new AddItem();
        }
    }

    private function UpdateFormData($formData, $formResults)
    {
        
    }

}
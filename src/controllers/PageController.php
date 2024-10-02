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
        if(!in_array($page, $_SESSION['allowedPages']))
        {
            echo '<script>alert("Invalid page requested, redirecting to homepage");</script>';
            $this->currentPage = new Home();
            return false;
        }
        switch($page)
        {
            case 'Register':
                if($this->dataExtractor->getPostVar('formDataName') === 'Register')
                {
                    $validatedInput = $this->inputValidator->validateInput(REGISTERFORMDATA);
                    if(!$this->containsErrors($validatedInput))
                    {
                        include_once "models/DataTypes.php";
                        $user = new User(
                            0, 
                            $this->dataExtractor->getPostVar('Name'),
                            $this->dataExtractor->getPostVar('Email'),
                            $this->dataExtractor->getPostVar('Password'),
                            $this->dataExtractor->getPostVar('DateOfBirth'),
                            $this->dataExtractor->getPostVar('Gender'),
                            "", 
                            false
                        );
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
                break;
            case 'Login':
                if($this->dataExtractor->getPostVar('formDataName') === 'Login')
                {
                    $validatedInput = $this->inputValidator->validateInput(LOGINFORMDATA);
                    if(!$this->containsErrors($validatedInput))
                    {
                        $email = $this->dataExtractor->getPostVar('Email');
                        $_SESSION['user'] = getUserFromFile($email);
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
                break;
                case 'Logout':
                    session_unset();
                    updateAllowedPages();
                    $this->currentPage = new Home();
                    break;
            default:
                $this->currentPage = new $page();
                break;
        }
        return $page;
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

}
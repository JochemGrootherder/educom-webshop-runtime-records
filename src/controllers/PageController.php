<?php
include 'DataExtractor.php';

//include all files in pages directory
foreach (glob("views/*.php") as $filename)
{
    include_once $filename;
}

Class PageController
{
    private $dataExtractor;

    function __construct()
    {
        $this->dataExtractor = new DataExtractor();
    }

    public function ExecuteAction()
    {
        $requestedPage = $this->GetRequestedPage();
        $pageName = $this->HandlePageRequest($requestedPage);
        $this->ShowPage($pageName);
    }

    private function ShowPage($pageName)
    {
        $currentPage = NULL;
        if(!in_array($pageName, $_SESSION['allowedPages']))
        {
            echo '<script>alert("Invalid page requested, redirecting to homepage");</script>';
            $currentPage = new Home();
        }
        else
        {
            $currentPage = new $pageName();
        }
        $currentPage->showBodySection();
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
        switch($page)
        {
            /*case 'register.php':
                if(validateInput("register"))
                {
                    $email = getPostVar('Email');
                    $name = getPostVar('Name');
                    $password = getPostVar('Password');
                    writeUserToFile($email, $name, $password);
                    return "login.php";
                }
                break;
            case 'login.php':
                if(validateInput("login"))
                {
                    $email = getPostVar('Email');
                    $_SESSION['user'] = getUserFromFile($email);
                    updateAllowedPages();
                    return  "home.php";
                }
                break;
                case 'logout.php':
                    session_unset();
                    updateAllowedPages();
                    return 'home.php';
                    break;*/
            default:
                break;
        }
        return $page;
    }

}
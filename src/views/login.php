<?php
include_once __DIR__.'/FormPage.php';
class Login extends FormPage
{
    public $formResults;

    public function __construct()
    {
        $this->formResults = $this->CreateEmptyFormResults(LOGINFORMDATA);
    }

    public static function WithResults($formResults)
    {
        $instance = new self();
        $instance->formResults = $formResults;
        return $instance;
    }
    function showTitle()
    {
        echo "Login";

    }
    function showBody()
    {
        $this->showForm(LOGINFORMDATA, $this->formResults, 'Login', 'Login', 'Login', 'Login');
    }
}
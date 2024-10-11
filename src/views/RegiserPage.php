<?php
include_once __DIR__.'/FormPage.php';
class RegisterPage extends FormPage
{
    private $formResults;
    public function __construct()
    {
        $this->formResults = $this->CreateEmptyFormResults(REGISTERFORMDATA);
    }

    public static function WithResults($formResults)
    {
        $instance = new self();
        $instance->formResults = $formResults;
        return $instance;
    }
    function showTitle()
    {
        echo "Register";

    }
    function showBody()
    {
        $this->showForm(REGISTERFORMDATA, $this->formResults, 'Register', 'RegisterPage', 'Register', 'Register');
    }
}
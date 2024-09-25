<?php
include_once 'FormPage.php';
class Register extends FormPage
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
        $this->showForm(REGISTERFORMDATA, $this->formResults, 'Register', 'Register', 'Register', 'Register');
    }
}
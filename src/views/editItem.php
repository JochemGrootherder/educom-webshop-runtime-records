<?php
include 'FormPage.php';

class EditItem extends FormPage
{
    private $formResults;
    
    public function __construct()
    {

    }
    public static function WithResults($formResults)
    {
        $instance = new self();
        $instance->formResults = $formResults;
        return $instance;
    }
    function showTitle()
    {
        echo "EditItem";

    }
    function showBody()
    {
        $this->showForm(REGISTERFORMDATA, $this->formResults, 'EditItem', 'EditItem', 'Edit item', 'Edit');
    }
}
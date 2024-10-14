<?php
include_once __DIR__.'/FormPage.php';

class AddItemPage extends FormPage
{
    private $formResults;
    private $formData;
    
    public function __construct()
    {
        $this->formData = ADDITEMFORMDATA;
        $this->formResults = $this->CreateEmptyFormResults($this->formData);
    }
    public static function WithResults($formData, $formResults)
    {
        $instance = new self();
        $instance->formData = $formData;
        $instance->formResults = $formResults;
        return $instance;
    }
    function showTitle()
    {
        echo "Add Item";

    }
    function showBody()
    {
        $this->showForm($this->formData, $this->formResults, 'AddItem', 'AddItem', 'Add item', 'Add');

    }
}
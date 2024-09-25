<?php
include_once 'FormPage.php';
class Register extends FormPage
{
    function showTitle()
    {
        echo "Register";

    }
    function showBody()
    {
        $emptyFormResults = $this->createEmptyFormResults($this->formData);
        $this->showForm($this->formData, $emptyFormResults, 'test', 'Register', 'test', 'yeet');
    }

    

    private $formData = [
        'Name'  => ['label' => 'Full Name', 'type' => 'text', 'placeholder' => 'Full name', 'validations' => ["notEmpty", "onlyCharacters"]],
        'Email' => ['label' => 'Email', 'type' => 'text', 'placeholder' => 'Example@example.com', 'validations' => ["notEmpty", "validEmail", "uniqueEmail", "toLowerCase"]],
        'Password' => ['label' => 'Password', 'type' => 'password', 'placeholder' => 'Password', 'validations' => ["notEmpty", "minLength:8", "containsUppercase", "containsLowercase", "containsNumber", "containsSpecialChar"]],
        'ConfirmPassword' => ['label' => 'Confirm Password', 'type' => 'password', 'placeholder' => 'Confirm Password', 'validations' => ["matchesPassword"]]
            ];

}
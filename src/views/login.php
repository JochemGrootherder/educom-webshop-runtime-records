<?php
include_once 'FormPage.php';
class Login extends FormPage
{
    function showTitle()
    {
        echo "Login";

    }
    function showBody()
    {
        $emptyFormResults = $this->createEmptyFormResults($this->formData);
        $this->showForm($this->formData, $emptyFormResults, 'test', 'Login', 'test', 'yeet');
    }

    

    private $formData = [
        'Email' => ['label' => 'Email', 'type' => 'text', 'placeholder' => 'Example@example.com', 'validations' => ["notEmpty", "loginValid", "toLowerCase", "emailExists"]],
        'Password' => ['label' => 'Password', 'type' => 'password', 'placeholder' => 'Password', 'validations' => []]
    ];

}
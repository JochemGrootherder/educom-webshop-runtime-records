<?php

class User
{
    public function __construct(int $id = 0
                            , string $name, string $email
                            , string $password, string $date_of_birth
                            , string $gender, string $search_criteria, bool $admin)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->date_of_birth = $date_of_birth;
        $this->gender = $gender;
        $this->search_criteria = $search_criteria;
        $this->admin = $admin;
    }
    public $id;
    public $name;
    public $email;
    public $password;
    public $date_of_birth;
    public $gender;
    public $search_criteria;
    public $admin;

}
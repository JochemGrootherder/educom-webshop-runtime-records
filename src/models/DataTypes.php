<?php
ENUM TYPES: string
{
    case CD = "CD";
    case VINYL = "VINYL";
    case CASETTE = "CASETTE";
};

class Item
{
    public function __construct(int $id = 0
                            , string $title, string $description
                            , int $year, float $price
                            , TYPES $type, int $stock, Date $dateAdded = new Date('Y-m-j'))
    {
        $this->Id = $id;
        $this->Title = $title;
        $this->Description = $desription;
        $this->Year = $year;
        $this->Price = $price;
        $this->Type = $type;
        $this->Stock = $stock;
        $this->DateAdded = $dateAdded;
    }
    public $Id;
    public $Title;
    public $Description;
    public $Year;
    public $Price;
    public $Type;
    public $Stock;
    public $DateAdded;
}

class User
{
    public function __construct(int $id = 0
                            , string $name, string $email
                            , string $password, Date $dateOfBirth
                            , string $gender, string $searchCriteria, bool $admin)
    {
        $this->Id = $id;
        $this->Name = $name;
        $this->Email = $email;
        $this->Password = $password;
        $this->DateOfBirth = $dateOfBirth;
        $this->Gender = $gender;
        $this->SearchCriteria = $searchCriteria;
        $this->Admin = $admin;
    }
    public $Id;
    public $Name;
    public $Email;
    public $Password;
    public $DateOfBirth;
    public $Gender;
    public $SearchCriteria;
    public $Admin;

}

class Order
{
    public function __construct(int $id, int $userId, Date $date)
    {
        $this->Id = $id;
        $this->UserId = $userId;
        $this->Date = $date;
    }

    public $Id;
    public $UserId;
    public $Date;

}

class OrderLine
{
    public function __construct(int $id, int $orderId, int $itemId, int $amount)
    {
        $this->Id = $id;
        $this->OrderId = $orderId;
        $this->ItemId = $itemId;
        $this->Amount = $amount;
    }

    public $Id;
    public $OrderId;
    public $ItemId;
    public $Amount;
}

class Genre
{
    public function __construct(string $name, int $itemId)
    {
        $this->Name = $name;
        $this->ItemId = $itemId;
    }

    public $Name;
    public $ItemId;
}

class Artist
{
    public function __construct(string $name, int $itemId)
    {
        $this->Name = $name;
        $this->ItemId = $itemId;
    }

    public $Name;
    public $ItemId;
}
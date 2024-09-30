<?php
ENUM TYPES: string
{
    case CD = "CD";
    case VINYL = "VINYL";
    case CASETTE = "CASETTE";
};

class DataType
{

}

class Item extends DataType
{
    public function __construct(int $id = 0
                            , string $title, string $description
                            , int $year, float $price
                            , TYPES $type, int $stock, Date $date_added = new Date('Y-m-j'))
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $desription;
        $this->year = $year;
        $this->price = $price;
        $this->type = $type;
        $this->Stock = $stock;
        $this->date_added = $date_added;
    }
    public $id;
    public $title;
    public $description;
    public $year;
    public $price;
    public $type;
    public $stock;
    public $date_added;
}

class User extends DataType
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

class Order extends DataType
{
    public function __construct(int $id, int $user_id, string $date)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->date = $date;
    }

    public $id;
    public $user_id;
    public $date;

}

class OrderLine extends DataType
{
    public function __construct(int $id, int $order_id, int $item_id, int $amount)
    {
        $this->id = $id;
        $this->order_id = $order_id;
        $this->item_id = $item_id;
        $this->amount = $amount;
    }

    public $id;
    public $order_id;
    public $item_id;
    public $amount;
}

class Genre extends DataType
{
    public function __construct(string $name, int $item_id)
    {
        $this->name = $name;
        $this->item_id = $item_id;
    }

    public $name;
    public $item_id;
}

class Artist extends DataType
{
    public function __construct(string $name, int $item_id)
    {
        $this->name = $name;
        $this->item_id = $item_id;
    }

    public $name;
    public $itemid;
}
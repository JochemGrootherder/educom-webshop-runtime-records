<?php

class Item
{
    protected int $id;
    protected string $title;
    protected array $artists;
    protected array $genres;
    protected string $description;
    protected string $year;
    protected float $price;
    protected string $type;
    protected int $stock;
    protected string $date_added;
    protected array $images;

    public function __construct()
    {
        $this->id = 0;
        $this->title = '';
        $this->artists = [];
        $this->genres = [];
        $this->description = '';
        $this->year = 0;
        $this->price = 0.0;
        $this->type = '';
        $this->stock = 0;
        $this->date_added = '';
        $this->images = [];
    }

    public function ConvertRowToDataType($row)
    {
        $this->id = $row['id'];
        $this->title = $row['title'];
        $this->description = $row['description'];
        $this->year = $row['year'];
        $this->price = $row['price'];
        $this->type = $row['type'];
        $this->stock = $row['stock'];
        $this->date_added = $row['date_added'];
    }

	/**
	 * Get the value of id
	 *
	 * @return  mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set the value of id
	 *
	 * @param   mixed  $id  
	 *
	 * @return  self
	 */
	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	/**
	 * Get the value of title
	 *
	 * @return  mixed
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Set the value of title
	 *
	 * @param   mixed  $title  
	 *
	 * @return  self
	 */
	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * Get the value of artists
	 *
	 * @return  mixed
	 */
	public function getArtists()
	{
		return $this->artists;
	}

	/**
	 * Set the value of artists
	 *
	 * @param   mixed  $artists  
	 *
	 * @return  self
	 */
	public function setArtists($artists)
	{
		$this->artists = $artists;

		return $this;
	}

	/**
	 * Get the value of genres
	 *
	 * @return  mixed
	 */
	public function getGenres()
	{
		return $this->genres;
	}

	/**
	 * Set the value of genres
	 *
	 * @param   mixed  $genres  
	 *
	 * @return  self
	 */
	public function setGenres($genres)
	{
		$this->genres = $genres;

		return $this;
	}

	/**
	 * Get the value of description
	 *
	 * @return  mixed
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Set the value of description
	 *
	 * @param   mixed  $description  
	 *
	 * @return  self
	 */
	public function setDescription($description)
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * Get the value of year
	 *
	 * @return  mixed
	 */
	public function getYear()
	{
		return $this->year;
	}

	/**
	 * Set the value of year
	 *
	 * @param   mixed  $year  
	 *
	 * @return  self
	 */
	public function setYear($year)
	{
		$this->year = $year;

		return $this;
	}

	/**
	 * Get the value of price
	 *
	 * @return  mixed
	 */
	public function getPrice()
	{
		return $this->price;
	}

	/**
	 * Set the value of price
	 *
	 * @param   mixed  $price  
	 *
	 * @return  self
	 */
	public function setPrice($price)
	{
		$this->price = $price;

		return $this;
	}

	/**
	 * Get the value of type
	 *
	 * @return  mixed
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Set the value of type
	 *
	 * @param   mixed  $type  
	 *
	 * @return  self
	 */
	public function setType($type)
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * Get the value of stock
	 *
	 * @return  mixed
	 */
	public function getStock()
	{
		return $this->stock;
	}

	/**
	 * Set the value of stock
	 *
	 * @param   mixed  $stock  
	 *
	 * @return  self
	 */
	public function setStock($stock)
	{
		$this->stock = $stock;

		return $this;
	}

	/**
	 * Get the value of date_added
	 *
	 * @return  mixed
	 */
	public function getDate_added()
	{
		return $this->date_added;
	}

	/**
	 * Set the value of date_added
	 *
	 * @param   mixed  $date_added  
	 *
	 * @return  self
	 */
	public function setDate_added($date_added)
	{
		$this->date_added = $date_added;

		return $this;
	}

	/**
	 * Get the value of images
	 *
	 * @return  mixed
	 */
	public function getImages()
	{
		return $this->images;
	}

	/**
	 * Set the value of images
	 *
	 * @param   mixed  $images  
	 *
	 * @return  self
	 */
	public function setImages($images)
	{
		$this->images = $images;

		return $this;
	}
}
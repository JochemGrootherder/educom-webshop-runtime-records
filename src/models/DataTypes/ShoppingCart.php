<?php

class ShoppingCart
{
    private $id;
    private $userId;
    private $dateLastUpdate;

    public function __construct()
    {
        $this->id = 0;
        $this->userId = null;
        $this->dateLastUpdate = '';
    }

	/**
	 * Get the value of id
	 *
	 * @return  mixed
	 */
	public function GetId()
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
	public function SetId($id)
	{
		$this->id = $id;

		return $this;
	}

	/**
	 * Get the value of userId
	 *
	 * @return  mixed
	 */
	public function GetUserId()
	{
		return $this->userId;
	}

	/**
	 * Set the value of userId
	 *
	 * @param   mixed  $userId  
	 *
	 * @return  self
	 */
	public function SetUserId($userId)
	{
		$this->userId = $userId;

		return $this;
	}

	/**
	 * Get the value of dateLastUpdate
	 *
	 * @return  mixed
	 */
	public function GetDateLastUpdate()
	{
		return $this->dateLastUpdate;
	}

	/**
	 * Set the value of dateLastUpdate
	 *
	 * @param   mixed  $dateLastUpdate  
	 *
	 * @return  self
	 */
	public function SetDateLastUpdate($dateLastUpdate)
	{
		$this->dateLastUpdate = $dateLastUpdate;

		return $this;
	}
}
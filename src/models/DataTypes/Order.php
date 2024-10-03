<?php

class Order
{
    public function __construct()
    {
        $this->id = 0;
        $this->user_id = 0;
        $this->date = '';
    }

    protected int $id;
    protected int $user_id;
    protected string $date;


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
	 * Get the value of user_id
	 *
	 * @return  mixed
	 */
	public function getUser_id()
	{
		return $this->user_id;
	}

	/**
	 * Set the value of user_id
	 *
	 * @param   mixed  $user_id  
	 *
	 * @return  self
	 */
	public function setUser_id($user_id)
	{
		$this->user_id = $user_id;

		return $this;
	}

	/**
	 * Get the value of date
	 *
	 * @return  mixed
	 */
	public function getDate()
	{
		return $this->date;
	}

	/**
	 * Set the value of date
	 *
	 * @param   mixed  $date  
	 *
	 * @return  self
	 */
	public function setDate($date)
	{
		$this->date = $date;

		return $this;
	}
}
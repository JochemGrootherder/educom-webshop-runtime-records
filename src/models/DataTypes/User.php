<?php

class User
{
    public function __construct()
    {
        $this->id = 0;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->date_of_birth = '';
        $this->gender = '';
        $this->search_criteria = '';
        $this->admin = false;
    }

    protected int $id;
    protected string $name;
    protected string $email;
    protected string $password;
    protected string $date_of_birth;
    protected string $gender;
    protected string $search_criteria;
    protected bool $admin;


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
	 * Get the value of name
	 *
	 * @return  mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set the value of name
	 *
	 * @param   mixed  $name  
	 *
	 * @return  self
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Get the value of email
	 *
	 * @return  mixed
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Set the value of email
	 *
	 * @param   mixed  $email  
	 *
	 * @return  self
	 */
	public function setEmail($email)
	{
		$this->email = $email;

		return $this;
	}

	/**
	 * Get the value of password
	 *
	 * @return  mixed
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Set the value of password
	 *
	 * @param   mixed  $password  
	 *
	 * @return  self
	 */
	public function setPassword($password)
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * Get the value of date_of_birth
	 *
	 * @return  mixed
	 */
	public function getDate_of_birth()
	{
		return $this->date_of_birth;
	}

	/**
	 * Set the value of date_of_birth
	 *
	 * @param   mixed  $date_of_birth  
	 *
	 * @return  self
	 */
	public function setDate_of_birth($date_of_birth)
	{
		$this->date_of_birth = $date_of_birth;

		return $this;
	}

	/**
	 * Get the value of gender
	 *
	 * @return  mixed
	 */
	public function getGender()
	{
		return $this->gender;
	}

	/**
	 * Set the value of gender
	 *
	 * @param   mixed  $gender  
	 *
	 * @return  self
	 */
	public function setGender($gender)
	{
		$this->gender = $gender;

		return $this;
	}

	/**
	 * Get the value of search_criteria
	 *
	 * @return  mixed
	 */
	public function getSearch_criteria()
	{
		return $this->search_criteria;
	}

	/**
	 * Set the value of search_criteria
	 *
	 * @param   mixed  $search_criteria  
	 *
	 * @return  self
	 */
	public function setSearch_criteria($search_criteria)
	{
		$this->search_criteria = $search_criteria;

		return $this;
	}

	/**
	 * Get the value of admin
	 *
	 * @return  mixed
	 */
	public function getAdmin()
	{
		return $this->admin;
	}

	/**
	 * Set the value of admin
	 *
	 * @param   mixed  $admin  
	 *
	 * @return  self
	 */
	public function setAdmin($admin)
	{
		$this->admin = $admin;

		return $this;
	}
}
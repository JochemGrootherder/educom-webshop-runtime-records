<?php

class Image
{
    public function __construct()
    {
        $this->id = 0;
    }

    protected int $id = 0;
    protected $image;

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
	 * Get the value of image
	 *
	 * @return  mixed
	 */
	public function getImage()
	{
		return $this->image;
	}

	/**
	 * Set the value of image
	 *
	 * @param   mixed  $image  
	 *
	 * @return  self
	 */
	public function setImage($image)
	{
		$this->image = $image;

		return $this;
	}
}
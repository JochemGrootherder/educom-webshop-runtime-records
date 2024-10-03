<?php

class OrderLine
{
    public function __construct()
    {
        $this->id = 0;
        $this->order_id = 0;
        $this->item_id = 0;
        $this->amount = 0;
    }

    protected int $id;
    protected int $order_id;
    protected int $item_id;
    protected int $amount;

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
	 * Get the value of order_id
	 *
	 * @return  mixed
	 */
	public function getOrder_id()
	{
		return $this->order_id;
	}

	/**
	 * Set the value of order_id
	 *
	 * @param   mixed  $order_id  
	 *
	 * @return  self
	 */
	public function setOrder_id($order_id)
	{
		$this->order_id = $order_id;

		return $this;
	}

	/**
	 * Get the value of item_id
	 *
	 * @return  mixed
	 */
	public function getItem_id()
	{
		return $this->item_id;
	}

	/**
	 * Set the value of item_id
	 *
	 * @param   mixed  $item_id  
	 *
	 * @return  self
	 */
	public function setItem_id($item_id)
	{
		$this->item_id = $item_id;

		return $this;
	}

	/**
	 * Get the value of amount
	 *
	 * @return  mixed
	 */
	public function getAmount()
	{
		return $this->amount;
	}

	/**
	 * Set the value of amount
	 *
	 * @param   mixed  $amount  
	 *
	 * @return  self
	 */
	public function setAmount($amount)
	{
		$this->amount = $amount;

		return $this;
	}
}
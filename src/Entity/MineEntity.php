<?php

namespace App\Entity;

class MineEntity
{
	protected $id;

	protected $type;

	protected $player;

	protected $hp;

	protected $pos;

	protected $workerSpace;

	protected $soldierSpace;

	protected $workers;

	protected $soldiers;

    public function __construct($id, $type, $player, $hp, $pos, $workerSpace, $soldierSpace, $workers, $soldiers)
    {
        $this->id = $id;
        $this->type = $type;
        $this->player = $player;
        $this->hp = $hp;
        $this->pos = $pos;
        $this->workerSpace = $workerSpace;
        $this->soldierSpace = $soldierSpace;
        $this->workers = $workers;
        $this->soldiers = $soldiers;
    }

    

	/**
	 * Get the value of id
	 */ 
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set the value of id
	 *
	 * @return  self
	 */ 
	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	/**
	 * Get the value of type
	 */ 
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Set the value of type
	 *
	 * @return  self
	 */ 
	public function setType($type)
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * Get the value of player
	 */ 
	public function getPlayer()
	{
		return $this->player;
	}

	/**
	 * Set the value of player
	 *
	 * @return  self
	 */ 
	public function setPlayer($player)
	{
		$this->player = $player;

		return $this;
	}

	/**
	 * Get the value of hp
	 */ 
	public function getHp()
	{
		return $this->hp;
	}

	/**
	 * Set the value of hp
	 *
	 * @return  self
	 */ 
	public function setHp($hp)
	{
		$this->hp = $hp;

		return $this;
	}

	/**
	 * Get the value of pos
	 */ 
	public function getPos()
	{
		return $this->pos;
	}

	/**
	 * Set the value of pos
	 *
	 * @return  self
	 */ 
	public function setPos($pos)
	{
		$this->pos = $pos;

		return $this;
	}

	/**
	 * Get the value of workerSpace
	 */ 
	public function getWorkerSpace()
	{
		return $this->workerSpace;
	}

	/**
	 * Set the value of workerSpace
	 *
	 * @return  self
	 */ 
	public function setWorkerSpace($workerSpace)
	{
		$this->workerSpace = $workerSpace;

		return $this;
	}

	/**
	 * Get the value of soldierSpace
	 */ 
	public function getSoldierSpace()
	{
		return $this->soldierSpace;
	}

	/**
	 * Set the value of soldierSpace
	 *
	 * @return  self
	 */ 
	public function setSoldierSpace($soldierSpace)
	{
		$this->soldierSpace = $soldierSpace;

		return $this;
	}

	/**
	 * Get the value of workers
	 */ 
	public function getWorkers()
	{
		return $this->workers;
	}

	/**
	 * Set the value of workers
	 *
	 * @return  self
	 */ 
	public function setWorkers($workers)
	{
		$this->workers = $workers;

		return $this;
	}

	/**
	 * Get the value of soldiers
	 */ 
	public function getSoldiers()
	{
		return $this->soldiers;
	}

	/**
	 * Set the value of soldiers
	 *
	 * @return  self
	 */ 
	public function setSoldiers($soldiers)
	{
		$this->soldiers = $soldiers;

		return $this;
    }
    
}
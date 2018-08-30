<?php

namespace App\Entity;

class BaseEntity
{
    protected $id;
    protected $type;
    protected $player;
    protected $playerId;
    protected $hp;
    protected $main;
    protected $pos;
    protected $workerSpace;
    protected $soldierSpace;
    protected $workers;
    protected $soldiers;
    protected $cost;
    protected $maxHP;

    public function __construct($args)
    {
        $this->hydrate($args);
    }

    private function hydrate($args)
    {
        if (is_array($args)){
            if (isset($args['id'])) {
                $this->id = ($args['id']);
            }
            if (isset($args['type'])){
                $this->setType($args['type']);
            }
            if (isset($args['player'])){
                $this->setPlayer($args['player']);
            }
            if (isset($args['playerId'])){
                $this->setPlayerId($args['playerId']);
            }
            if (isset($args['HP'])){
                $this->setHp($args['HP']);
            }
            if (isset($args['main'])){
                $this->setMain($args['main']);
            }
            if (isset($args['pos'])){
                $this->setPos($args['pos']);
            }
            if (isset($args['workerSpace'])){
                $this->setWorkerSpace($args['workerSpace']);
            }
            if (isset($args['soldierSpace'])){
                $this->setSoldierSpace($args['soldierSpace']);
            }
            if (isset($args['workers'])){
                $this->setWorkers($args['workers']);
            }
            if (isset($args['soldiers'])){
                $this->setSoldiers($args['soldiers']);
            }
        }
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
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
     * Get the value of playerId
     */ 
    public function getPlayerId()
    {
        return $this->playerId;
    }

    /**
     * Set the value of playerId
     *
     * @return  self
     */ 
    public function setPlayerId($playerId)
    {
        $this->playerId = $playerId;

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
     * Get the value of main
     */ 
    public function getMain()
    {
        return $this->main;
    }

    /**
     * Set the value of main
     *
     * @return  self
     */ 
    public function setMain($main)
    {
        $this->main = $main;

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

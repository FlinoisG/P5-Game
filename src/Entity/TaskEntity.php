<?php

namespace App\Entity;

class TaskEntity
{
    protected $id;
    protected $action; 
    protected $subject; 
    protected $startOrigin; 
    protected $startPos; 
    protected $targetOrigin; 
    protected $targetPos; 
    protected $startTime; 
    protected $endTime; 
    protected $author;

    public function __construct($args)
    {
		$this->hydrate($args);
    }

	private function hydrate ($args)
	{
		if (is_array($args)){
            if (isset($args['id'])) {
                $this->id = $args['id'];
			}
			if (isset($args['action'])) {
                $this->setAction($args['action']);
			}
			if (isset($args['subject'])) {
                $this->setSubject($args['subject']);
			}
			if (isset($args['startOrigin'])) {
                $this->setStartOrigin($args['startOrigin']);
			}
			if (isset($args['startPos'])) {
                $this->setStartPos($args['startPos']);
			}
			if (isset($args['targetOrigin'])) {
                $this->setTargetOrigin($args['targetOrigin']);
			}
			if (isset($args['targetPos'])) {
                $this->setTargetPos($args['targetPos']);
            }
            if (isset($args['startTime'])) {
                $this->setStartTime($args['startTime']);
			}
			if (isset($args['endTime'])) {
                $this->setEndTime($args['endTime']);
			}
			if (isset($args['author'])) {
                $this->setAuthor($args['author']);
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
     * Get the value of action
     */ 
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set the value of action
     *
     * @return  self
     */ 
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get the value of subject
     */ 
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set the value of subject
     *
     * @return  self
     */ 
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get the value of startOrigin
     */ 
    public function getStartOrigin()
    {
        return $this->startOrigin;
    }

    /**
     * Set the value of startOrigin
     *
     * @return  self
     */ 
    public function setStartOrigin($startOrigin)
    {
        $this->startOrigin = $startOrigin;

        return $this;
    }

    /**
     * Get the value of startPos
     */ 
    public function getStartPos()
    {
        return $this->startPos;
    }

    /**
     * Set the value of startPos
     *
     * @return  self
     */ 
    public function setStartPos($startPos)
    {
        $this->startPos = $startPos;

        return $this;
    }

    /**
     * Get the value of targetOrigin
     */ 
    public function getTargetOrigin()
    {
        return $this->targetOrigin;
    }

    /**
     * Set the value of targetOrigin
     *
     * @return  self
     */ 
    public function setTargetOrigin($targetOrigin)
    {
        $this->targetOrigin = $targetOrigin;

        return $this;
    }

    /**
     * Get the value of targetPos
     */ 
    public function getTargetPos()
    {
        return $this->targetPos;
    }

    /**
     * Set the value of targetPos
     *
     * @return  self
     */ 
    public function setTargetPos($targetPos)
    {
        $this->targetPos = $targetPos;

        return $this;
    }

    /**
     * Get the value of startTime
     */ 
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set the value of startTime
     *
     * @return  self
     */ 
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get the value of endTime
     */ 
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set the value of endTime
     *
     * @return  self
     */ 
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get the value of author
     */ 
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set the value of author
     *
     * @return  self
     */ 
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

}
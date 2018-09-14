<?php

namespace App\Entity;

class UserEntity
{
	protected $id;
	protected $username;
	protected $email;
	protected $password;
	protected $newUser;
	protected $token;
	protected $tokenExp;
	protected $score;
	protected $metal;
	protected $bestScore;
	protected $totalScore;

    public function __construct($args)
    {
		$this->hydrate($args);
    }

	private function hydrate ($args)
	{
        if (is_array($args)){
            if (isset($args["id"])){
                $this->id = $args["id"];
            }
            if (isset($args["username"])){
                $this->username = $args["username"];
            }
            if (isset($args["email"])){
                $this->email = $args["email"];
            }
            if (isset($args["password"])){
                $this->password = $args["password"];
            }
            if (isset($args["newUser"])){
                $this->newUser = $args["newUser"];
            }
            if (isset($args["token"])){
                $this->token = $args["token"];
            }
            if (isset($args["token_exp"])){
                $this->tokenExp = $args["token_exp"];
            }
            if (isset($args["score"])){
                $this->score = $args["score"];
            }
            if (isset($args["metal"])){
                $this->metal = $args["metal"];
			}
			if (isset($args["bestScore"])){
                $this->bestScore = $args["bestScore"];
			}
			if (isset($args["totalScore"])){
                $this->totalScore = $args["totalScore"];
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
	 * Get the value of username
	 */ 
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Set the value of username
	 *
	 * @return  self
	 */ 
	public function setUsername($username)
	{
		$this->username = $username;

		return $this;
	}

	/**
	 * Get the value of email
	 */ 
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Set the value of email
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
	 */ 
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Set the value of password
	 *
	 * @return  self
	 */ 
	public function setPassword($password)
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * Get the value of newUser
	 */ 
	public function getNewUser()
	{
		return $this->newUser;
	}

	/**
	 * Set the value of newUser
	 *
	 * @return  self
	 */ 
	public function setNewUser($newUser)
	{
		$this->newUser = $newUser;

		return $this;
	}

	/**
	 * Get the value of token
	 */ 
	public function getToken()
	{
		return $this->token;
	}

	/**
	 * Set the value of token
	 *
	 * @return  self
	 */ 
	public function setToken($token)
	{
		$this->token = $token;

		return $this;
	}

	/**
	 * Get the value of token_exp
	 */ 
	public function getToken_exp()
	{
		return $this->token_exp;
	}

	/**
	 * Set the value of token_exp
	 *
	 * @return  self
	 */ 
	public function setToken_exp($token_exp)
	{
		$this->token_exp = $token_exp;

		return $this;
	}

	/**
	 * Get the value of score
	 */ 
	public function getScore()
	{
		return $this->score;
	}

	/**
	 * Set the value of score
	 *
	 * @return  self
	 */ 
	public function setScore($score)
	{
		$this->score = $score;

		return $this;
	}

	/**
	 * Get the value of metal
	 */ 
	public function getMetal()
	{
		return $this->metal;
	}

	/**
	 * Set the value of metal
	 *
	 * @return  self
	 */ 
	public function setMetal($metal)
	{
		$this->metal = $metal;

		return $this;
	}

	/**
	 * Get the value of bestScore
	 */ 
	public function getBestScore()
	{
		return $this->bestScore;
	}

	/**
	 * Set the value of bestScore
	 *
	 * @return  self
	 */ 
	public function setBestScore($bestScore)
	{
		$this->bestScore = $bestScore;

		return $this;
	}

	/**
	 * Get the value of totalScore
	 */ 
	public function getTotalScore()
	{
		return $this->totalScore;
	}

	/**
	 * Set the value of totalScore
	 *
	 * @return  self
	 */ 
	public function setTotalScore($totalScore)
	{
		$this->totalScore = $totalScore;

		return $this;
	}
}
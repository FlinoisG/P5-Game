<?php

namespace App\Repository;

use PDO;
use App\Model\Repository;
use App\Service\sqlQueryService;

class UserRepository extends Repository
{

    /**
     * Get an encrypted password from the database
     * with the corresponding username
     *
     * @param string $username
     * @return string encrypted password
     */
    public function getPasswordWithUsername($username)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT password FROM game_users WHERE username = :username");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }

    /**
     * Get the Id corresponding to a username
     *
     * @param string $username
     * @return string Id
     */
    public function getIdWithUsername($username)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT id FROM game_users WHERE username = :username");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }

    /**
     * Get the newUser parameter corresponding
     * to a username
     *
     * @param string $username
     * @return string newUser
     */
    public function getNewUserWithUsername($username)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT newUser FROM game_users WHERE username = :username");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }

    /**
     * Get an username from the database
     * with the corresponding Id
     *
     * @param mixed $id
     * @return void
     */
    public function getUsernameWithId($id){
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT username FROM game_users WHERE id = :id");
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }

    /**
     * Get the newUser parameter corresponding
     * to an id
     *
     * @param mixed $userId
     * @return void
     */
    public function getNewUser($userId)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT newUser FROM game_users WHERE id = :userId");
        $query->bindParam(":userId", $userId, PDO::PARAM_STR);
        $query->execute();
        $newUser = $query->fetch();
        return $newUser['newUser'];
    }

    /**
     * Get the token corresponding to
     * a username
     *
     * @param string $username
     * @return string token
     */
    public function getTokenWithUsername($username)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT token FROM game_users WHERE username = :username");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }

    /**
     * Get the whole entry corresponding to
     * an email
     *
     * @param string $email
     * @return array
     */
    public function getEverythingWithEmail($email)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT * FROM game_users WHERE email = :email");
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->execute();
        $response = $query->fetchAll();
        return $response;
    }

    /**
     * Check if an email is registered in the database
     * by returning the corresponding username
     *
     * @param string $email
     * @return string username
     */
    public function checkEmail($email)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT username FROM game_users WHERE email = :email");
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }

    /**
     * Registers a new user into the database
     *
     * @param string $username
     * @param string $email
     * @param string $hashedPassword
     * @return void
     */
    public function registerUser($username, $email, $hashedPassword)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("INSERT INTO game_users (username, email, password)
        VALUES (:username, :email, :hashedPassword)");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->bindParam(":hashedPassword", $hashedPassword, PDO::PARAM_STR);
        $query->execute();
    }

    /**
     * update token and token_exp at the
     * specified email
     *
     * @param string $hashedResetToken Encrypted 
     * token that will be sent to user in case he
     * forgot his password
     * @param string $resetExpiration 
     * @param [type] $email
     * @return void
     */
    public function updateToken($hashedResetToken, $resetExpiration, $email)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("UPDATE game_users SET token = :hashedResetToken, token_exp = :resetExpiration WHERE email = :email");
        $query->bindParam(":hashedResetToken", $hashedResetToken, PDO::PARAM_STR);
        $query->bindParam(":resetExpiration", $resetExpiration, PDO::PARAM_STR);
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->execute();
    }

    /**
     * Replace in the database the current
     * password with the one provided
     *
     * @param string $user
     * @param string $password
     * @return void
     */
    public function resetPassword($username, $hashedPassword)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare('UPDATE game_users SET password = \':hashedPassword\', token = \'\', token_exp = \'\' WHERE username = :username');
        $query->bindParam(":hashedPassword", $hashedPassword, PDO::PARAM_STR);
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->execute();
    }

    /*
    public function getIdByUsername($username){
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT ID FROM game_users WHERE username = :username");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }*/


    /**
     * gets every id and usernames from game_users
     *
     * @return array
     */
    public function getAllUsername(){
        $sqlQueryService = new sqlQueryService();
        $query = "SELECT id, username FROM game_users";
        $results = $sqlQueryService->sqlQueryService($query);
        foreach ($results as $result) {
            $usernames[$result["id"]] = $result["username"];
        }
        return $usernames;
    }

    /**
     * get the amount of metal left in a user's account
     *
     * @param string $username
     * @return int metal
     */
    public function getMetal($id)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT metal FROM game_users WHERE id = :id");
        $query->bindParam(":id", $id, PDO::PARAM_STR);
        $query->execute();
        $response = $query->fetch();
        return (int)$response[0];
    }

    /**
     * add metal to a user's account (can be negative)
     *
     * @param mixed $id
     * @param int $amount
     * @return void
     */
    public function addMetal($id, $amount)
    {
        $metal = $this->getMetal($id);
        $newMetalAmount = $metal + $amount;
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("UPDATE game_users SET metal = :metal WHERE id = :id");
        $query->bindParam(":metal", $newMetalAmount, PDO::PARAM_STR);
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }

    /**
     * Replace the newUser parameter of
     * an user in game_users
     *
     * @param mixed $userId Id of the user
     * @param integer $value New value to replace the old one
     * @return void
     */
    public function changeNewUser($userId, $value=0)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("UPDATE game_users SET newUser = :value WHERE id = :userId");
        $query->bindParam(":value", $value, PDO::PARAM_STR);
        $query->bindParam(":userId", $userId, PDO::PARAM_STR);
        $query->execute();
    }

}
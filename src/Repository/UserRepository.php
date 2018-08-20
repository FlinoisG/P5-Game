<?php

namespace App\Repository;

use PDO;
use App\Model\Repository;
use App\Service\sqlQueryService;

class UserRepository extends Repository
{

    public function getPasswordWithUsername($username)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT password FROM game_users WHERE username = :username");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }

    public function getIdWithUsername($username)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT id FROM game_users WHERE username = :username");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }

    public function getNewUserWithUsername($username)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT newUser FROM game_users WHERE username = :username");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }

    public function getTokenWithUsername($username)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT token FROM game_users WHERE username = :username");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }

    public function getEverythingWithEmail($email)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT * FROM game_users WHERE email = :email");
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->execute();
        $response = $query->fetchAll();
        return $response;
    }

    public function checkEmail($email)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT username FROM game_users WHERE email = :email");
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }

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
     * Replace in the database the current password with the one provided
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

    public function getIdByUsername($username){
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT ID FROM game_users WHERE username = :username");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }

    public function getUsernameById($id){
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT username FROM game_users WHERE id = :id");
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }

    public function getAllUsername(){
        $sqlQueryService = new sqlQueryService();
        $query = "SELECT id, username FROM game_users";
        $results = $sqlQueryService->sqlQueryService($query);
        foreach ($results as $result) {
            $usernames[$result["id"]] = $result["username"];
        }
        return $usernames;
    }

    public function getMetal($username)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT metal FROM game_users WHERE username = :username");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }

    public function addMetal($username, $amount)
    {
        $metal = $this->getMetal($username);
        $newMetalAmount = $metal + $amount;
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("UPDATE game_users SET metal = :metal WHERE username = :username");
        $query->bindParam(":metal", $newMetalAmount, PDO::PARAM_STR);
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }

    public function getNewUser($userId)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT newUser FROM game_users WHERE id = :userId");
        $query->bindParam(":userId", $userId, PDO::PARAM_STR);
        $query->execute();
        $newUser = $query->fetch();
        return $newUser['newUser'];
    }

    public function changeNewUser($userId, $value=0)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("UPDATE game_users SET newUser = :value WHERE id = :userId");
        $query->bindParam(":value", $value, PDO::PARAM_STR);
        $query->bindParam(":userId", $userId, PDO::PARAM_STR);
        $query->execute();
    }

}
<?php

namespace App\Repository;

use PDO;
use App\Model\Repository;
use App\Entity\BaseEntity;
use App\Repository\UserRepository;

class BaseRepository extends BuildingRepository
{

    protected $type = "base";

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Gets all bases from the database and return them 
     * in a array of BaseEntity objects
     *
     * @return array BaseEntity 
     */
    public function getBases()
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT * FROM game_bases");
        $query->execute();
        $bases = $query->fetchAll();
        $basesArray = [];
        for ($i=0; $i < sizeof($bases); $i++) { 
            $basesArray[$i] = new BaseEntity($bases[$i]);
        }
        return $bases;
    }

    /**
     * Gets a base from the database with his id and 
     * returns it as an BaseEntity object
     *
     * @param int $id
     * @return object BaseEntity
     */
    public function getById($id)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT * FROM game_bases WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $obj = new BaseEntity($query->fetch());
        return $obj;
    }

    /**
     * Get "main" from game_bases at specified id
     *
     * @param int $id
     * @return void
     */
    public function getMain($id)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT main FROM game_bases WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $obj = new BaseEntity($query->fetch());
        return $obj;
    }

    /**
     * Insert a new base in the database
     *
     * @param int $userId Id of the base owner
     * @param array $pos Position of the base
     * @param int $main 1 if the base is a main base, 0 if not
     * @return void
     */
    public function newBase($userId, $pos, $main = 0)
    {
        $userRepository = new UserRepository;

        if ($main === 1){
            $hp = 500;
        } else {
            $hp = 100;
        }
        $username = $userRepository->getUsernameWithId($userId);
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("INSERT INTO game_bases (player, HP, playerId, pos, main) VALUES (:username, :hp, :author, :pos, :main)");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->bindParam(":hp", $hp, PDO::PARAM_INT);
        $query->bindParam(":author", $userId, PDO::PARAM_STR);
        $query->bindParam(":pos", $pos, PDO::PARAM_STR);
        $query->bindParam(":main", $main, PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * Delets every entry in game_bases
     *
     * @return void
     */
    public function deleteEverything()
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("DELETE FROM game_bases");
        $query->execute();
    }

    
    
}

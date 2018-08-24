<?php

namespace App\Repository;

use PDO;
use App\Model\Repository;
use App\Entity\MineEntity;
use App\Service\GridService;

class MineRepository extends BuildingRepository
{

    private $type = "mine";

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Gets all mines from the database and return them 
     * in a array of MineEntity objects
     *
     * @param boolean $object if true, returns an array of object MineEntity
     * @return array MineEntity 
     */
    public function getMines($object = false)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT * FROM game_mines");
        $query->execute();
        $mines = $query->fetchAll();
        $minesArray = [];
        for ($i=0; $i < sizeof($mines); $i++) { 
            $minesArray[$i] = new MineEntity($mines[$i]);
        }
        if ($object){
            return $minesArray;
        } else {
            return $mines;
        }
    }

    /**
     * Gets a mine from the database with his id and 
     * returns it as an MineEntity object
     *
     * @param int $id
     * @return object MineEntity
     */
    public function getById($id)
    {
        $DBConnection = $this->getDBConnection();
        $table = 'game_'.$this->getType().'s';
        $query = $DBConnection->prepare("SELECT * FROM game_bases WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $obj = new BaseEntity($query->fetch());
        return $obj;
    }
    
    /**
     * Insert a new mine in the database
     *
     * @param int $userId Id of the mine owner
     * @param array $pos Position of the new mine
     * @return void
     */
    public function newMine($userId, $pos)
    {
        $gridService = new GridService;
        $userRepository = new UserRepository;
        
        // Get username from user Id
        $username = $userRepository->getUsernameById($userId);
        
        // Get metal nodes around the new mine's position
        $posArr = str_replace(array( '[', ']' ), '', $pos);
        $posArr = explode(',', $posArr);
        $posArr = $gridService->gridToCoordinates($posArr[0], $posArr[1]);
        $metalNodes = $gridService->getMetalNodes($posArr);
        $metalNodes = json_encode($metalNodes);
        
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("INSERT INTO game_mines (player, playerId, pos, metalNodes) VALUES (:player, :playerId, :pos, :metalNodes)");
        $query->bindParam(":player", $username, PDO::PARAM_STR);
        $query->bindParam(":playerId", $userId, PDO::PARAM_INT);
        $query->bindParam(":pos", $pos, PDO::PARAM_STR);
        $query->bindParam(":metalNodes", $metalNodes, PDO::PARAM_STR);
        $query->execute();
        var_dump("ok");
    }

    /**
     * returns the parameter metalNodes from
     * the specified mine's id
     *
     * @param mixed $id
     * @return void
     */
    public function getMetalNodes($id)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT metalNodes FROM game_mines WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $responce = new BaseEntity($query->fetch());
        return $responce;
    }

    /**
     * update metalNode of a mine inside game_bases
     *
     * @param mixed $id
     * @param array $metalNodes
     * @return void
     */
    public function setMetalNodes($id, $metalNodes)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("UPDATE game_mines SET metalNodes = :metalNodes WHERE id = :id");
        var_dump($metalNodes);
        $metalNodes = json_encode($metalNodes, true);
        var_dump($metalNodes);
        $query->bindParam(':metalNodes', $metalNodes, PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }
    
}

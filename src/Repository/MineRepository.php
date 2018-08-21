<?php

namespace App\Repository;

use PDO;
use App\Model\Repository;
use App\Entity\MineEntity;

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
     * @return array MineEntity 
     */
    public function getMines()
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT * FROM game_mines");
        $query->execute();
        $mines = $query->fetchAll();
        $minesArray = [];
        for ($i=0; $i < sizeof($mines); $i++) { 
            $minesArray[$i] = new MineEntity($mines[$i]);
        }
        return $mines;
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
        // Get usernam from user Id
        $userRepository = new UserRepository;
        $username = $userRepository->getUsernameById($userId);

        // Get metal nodes around the new mine's position
        $posArr = str_replace(array( '[', ']' ), '', $pos);
        $posArr = explode(',', $posArr);
        $posArr = $gridService->gridToCoordinates($posArr[0], $posArr[1]);
        $metalNodes = $gridService->getMetalNodes($posArr);
        $metalNodes = json_encode($metalNodes);

        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("INSERT INTO game_mines (player, playerId, pos, main) VALUES (':username', ':author', ':pos', ':main')");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->bindParam(":author", $userId, PDO::PARAM_STR);
        $query->bindParam(":pos", $pos, PDO::PARAM_STR);
        $query->bindParam(":metalNodes", $metalNodes, PDO::PARAM_INT);
        $query->execute();
    }
    
}

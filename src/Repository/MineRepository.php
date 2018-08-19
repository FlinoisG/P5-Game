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
    

    public function newMine($buildingType, $username, $author, $pos, $metalNodes)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("INSERT INTO game_mines (player, playerId, pos, main) VALUES (':username', ':author', ':pos', ':main')");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->bindParam(":author", $author, PDO::PARAM_STR);
        $query->bindParam(":pos", $pos, PDO::PARAM_STR);
        $query->bindParam(":metalNodes", $metalNodes, PDO::PARAM_INT);
        $query->execute();
    }
    
}

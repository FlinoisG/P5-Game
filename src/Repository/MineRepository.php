<?php

namespace App\Repository;

use PDO;
use App\Model\Repository;
use App\Entity\MineEntity;

class MineRepository extends ObjectRepository
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

    /*
    public function getUnits($unit, $id)
    {
        $DBConnection = $this->getDBConnection();
        if ($unit === "worker") {
            $query = $DBConnection->prepare("SELECT workers FROM game_mines WHERE id= :id");
        } else if ($unit === "soldier") {
            $query = $DBConnection->prepare("SELECT soldiers FROM game_mines WHERE id= :id");
        } else {
            return false;
            die();
        }
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $unitNumber = $query->fetch()[0];
        return $unitNumber;
    }
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

    public function getWorkerSpaceLeft($id)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT workerSpace, workers FROM game_bases WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch();
        $spaceLeft = ($result["workerSpace"] - $result["workers"]) + 1;
        return $spaceLeft;
    }

    public function getSoldierSpaceLeft($id)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT soldierSpace, soldiers FROM game_bases WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch();
        $spaceLeft = ($result["soldierSpace"] - $result["soldiers"]) + 1;
        return $spaceLeft;
    }

    public function buyWorkers($id, $amount=1)
    {
        $DBConnection = $this->getDBConnection();
        $baseUnit = $this->getUnits("workers", $id);
        $baseUnit = $baseUnit + $amount;
        $query = $DBConnection->prepare("UPDATE game_mines SET workers= :baseUnit WHERE id= :id");
        $query->bindParam(':baseUnit', $baseUnit, PDO::PARAM_INT);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
    } 

    public function buySoldiers($id, $amount=1)
    {
        $DBConnection = $this->getDBConnection();
        $baseUnit = $this->getUnits("soldiers", $id);
        $baseUnit = $baseUnit + $amount;
        $query = $DBConnection->prepare("UPDATE game_mines SET soldiers= :baseUnit WHERE id= :id");
        $query->bindParam(':baseUnit', $baseUnit, PDO::PARAM_INT);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
    } 
    

    
}

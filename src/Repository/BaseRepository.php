<?php

namespace App\Repository;

use PDO;
use App\Model\Repository;
use App\Entity\BaseEntity;

class BaseRepository extends ObjectRepository
{

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

    public function getUnits($unit, $id)
    {
        $DBConnection = $this->getDBConnection();
        if ($unit === "worker") {
            $query = $DBConnection->prepare("SELECT workers FROM game_bases WHERE id= :id");
        } else if ($unit === "soldier") {
            $query = $DBConnection->prepare("SELECT soldiers FROM game_bases WHERE id= :id");
        } else {
            return false;
            die();
        }
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $unitNumber = $query->fetch()[0];
        return $unitNumber;
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
    
}

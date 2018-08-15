<?php

namespace App\Repository;

use PDO;
use App\Model\Repository;
use App\Entity\BaseEntity;

class BuildingRepository extends Repository
{

    public function buyUnits($unitType, $id, $amount = 1, $buildingType = null)
    {
        if (is_null($buildingType)){
            $buildingType = $this->getType();
        }
        $DBConnection = $this->getDBConnection();
        $baseUnit = $this->getUnits($unitType, $id, $buildingType);
        $baseUnit = $baseUnit + $amount;
        if ($buildingType === "base"){
            if ($unitType === "worker"){
                $query = $DBConnection->prepare("UPDATE game_bases SET workers= :baseUnit WHERE id= :id");
            } else if ($unitType === "soldier"){
                $query = $DBConnection->prepare("UPDATE game_bases SET soldiers= :baseUnit WHERE id= :id");
            } else {
                return false;
                die();
            }
        } else if ($buildingType === "mine"){
            if ($unitType === "worker"){
                $query = $DBConnection->prepare("UPDATE game_mines SET workers= :baseUnit WHERE id= :id");
            } else if ($unitType === "soldier"){
                $query = $DBConnection->prepare("UPDATE game_mines SET soldiers= :baseUnit WHERE id= :id");
            } else {
                return false;
                die();
            }
        } else {
            return false;
            die();
        }
        $query->bindParam(':baseUnit', $baseUnit, PDO::PARAM_INT);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }

    public function getSpaceLeft($unitType, $id, $buildingType = null)
    {
        $DBConnection = $this->getDBConnection();
        if (is_null($buildingType)){
            $buildingType = $this->getType();
        }
        if ($unitType === "worker"){
            if ($buildingType === "base"){
                $query = $DBConnection->prepare("SELECT workerSpace, workers FROM game_bases WHERE id = :id");
            } elseif ($buildingType === "mine"){
                $query = $DBConnection->prepare("SELECT workerSpace, workers FROM game_mines WHERE id = :id");
            } else {
                return false;
                die();
            }
        } elseif ($unitType === "soldier"){
            if ($buildingType === "base"){
                $query = $DBConnection->prepare("SELECT soldierSpace, soldiers FROM game_bases WHERE id = :id");
            } elseif ($buildingType === "mine"){
                $query = $DBConnection->prepare("SELECT soldierSpace, soldiers FROM game_mines WHERE id = :id");
            } else {
                return false;
                die();
            }
        } else {
            return false;
            die();
        }
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch();
        $spaceLeft = ($result[$unitType."Space"] - $result[$unitType."s"]) + 1;
        return $spaceLeft;
    }

    public function getUnits($unitType, $id, $buildingType = null)
    {
        $DBConnection = $this->getDBConnection();
        if (is_null($buildingType)){
            $buildingType = $this->getType();
        }
        if ($unitType === "worker") {
            if ($buildingType === "base"){
                $query = $DBConnection->prepare("SELECT workers FROM game_bases WHERE id= :id");
            } elseif ($buildingType === "mine"){
                $query = $DBConnection->prepare("SELECT workers FROM game_mines WHERE id= :id");
            } else {
                return false;
                die();
            }
        } else if ($unitType === "soldier") {
            if ($buildingType === "base"){
                $query = $DBConnection->prepare("SELECT soldiers FROM game_bases WHERE id= :id");
            } elseif ($buildingType === "mine"){
                $query = $DBConnection->prepare("SELECT soldiers FROM game_mines WHERE id= :id");
            } else {
                return false;
                die();
            }
        } else {
            return false;
            die();
        }
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $unitNumber = $query->fetch()[0];
        return $unitNumber;
    }

    /*
    public function buyWorkers($id, $amount = 1, $type = null)
    {
        if (is_null($type)){
            $type = $this->getType();
        }
        $DBConnection = $this->getDBConnection();
        $baseUnit = $this->getUnits("workers", $id);
        $baseUnit = $baseUnit + $amount;
        if ($type === "base"){
            $query = $DBConnection->prepare("UPDATE game_bases SET workers= :baseUnit WHERE id= :id");
        } else if ($type === "mine"){
            $query = $DBConnection->prepare("UPDATE game_mines SET workers= :baseUnit WHERE id= :id");
        }
        $query->bindParam(':baseUnit', $baseUnit, PDO::PARAM_INT);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
    } 

    public function buySoldiers($id, $amount=1, $type = null)
    {
        if (is_null($type)){
            $type = $this->getType();
        }
        $DBConnection = $this->getDBConnection();
        $baseUnit = $this->getUnits("soldiers", $id);
        $baseUnit = $baseUnit + $amount;
        if ($type === "base"){
            $query = $DBConnection->prepare("UPDATE game_bases SET soldiers= :baseUnit WHERE id= :id");
        } else if ($type === "mine"){
            $query = $DBConnection->prepare("UPDATE game_mines SET soldiers= :baseUnit WHERE id= :id");
        }
        $query->bindParam(':baseUnit', $baseUnit, PDO::PARAM_INT);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
    } 
    */

}
    
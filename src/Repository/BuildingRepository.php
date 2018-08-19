<?php

namespace App\Repository;

use PDO;
use App\Model\Repository;
use App\Entity\BaseEntity;
use App\Service\sqlQuery;

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
                $statement = "UPDATE game_bases SET workers= :baseUnit WHERE id= :id";
            } else if ($unitType === "soldier"){
                $statement = "UPDATE game_bases SET soldiers= :baseUnit WHERE id= :id";
            } else {
                return false;
                die();
            }
        } else if ($buildingType === "mine"){
            if ($unitType === "worker"){
                $statement = "UPDATE game_mines SET workers= :baseUnit WHERE id= :id";
            } else if ($unitType === "soldier"){
                $statement = "UPDATE game_mines SET soldiers= :baseUnit WHERE id= :id";
            } else {
                return false;
                die();
            }
        } else {
            return false;
            die();
        }
        $query = $DBConnection->prepare($statement);
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
                $statement = "SELECT workerSpace, workers FROM game_bases WHERE id = :id";
            } elseif ($buildingType === "mine"){
                $statement = "SELECT workerSpace, workers FROM game_mines WHERE id = :id";
            } else {
                return false;
                die();
            }
        } elseif ($unitType === "soldier"){
            if ($buildingType === "base"){
                $statement = "SELECT soldierSpace, soldiers FROM game_bases WHERE id = :id";
            } elseif ($buildingType === "mine"){
                $statement = "SELECT soldierSpace, soldiers FROM game_mines WHERE id = :id";
            } else {
                return false;
                die();
            }
        } else {
            return false;
            die();
        }
        $query = $DBConnection->prepare($statement);
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
                $statement = "SELECT workers FROM game_bases WHERE id= :id";
            } elseif ($buildingType === "mine"){
                $statement = "SELECT workers FROM game_mines WHERE id= :id";
            } else {
                return false;
                die();
            }
        } else if ($unitType === "soldier") {
            if ($buildingType === "base"){
                $statement = "SELECT soldiers FROM game_bases WHERE id= :id";
            } elseif ($buildingType === "mine"){
                $statement = "SELECT soldiers FROM game_mines WHERE id= :id";
            } else {
                return false;
                die();
            }
        } else {
            return false;
            die();
        }
        $query = $DBConnection->prepare($statement);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $unitNumber = $query->fetch()[0];
        return $unitNumber;
    }

    public function getPos($id, $buildingType = null)
    {
        $DBConnection = $this->getDBConnection();
        if (is_null($buildingType)){
            $buildingType = $this->getType();
        }
        if ($buildingType === "base") {
            $statement = "SELECT pos FROM game_bases WHERE id= :id";
        } else if ($buildingType === "mine") {
            $statement = "SELECT pos FROM game_mines WHERE id= :id";
        } else {
            return false;
            die();
        }
        $query = $DBConnection->prepare($statement);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $pos = $query->fetch();
        return $pos[0];
    }

    public function getAllUnit()
    {
        $sqlQuery = new sqlQuery();
        $query = "SELECT id, workers, soldiers FROM game_bases";
        $baseUnit = $sqlQuery->sqlQuery($query);
        $query = "SELECT id, workers, soldiers FROM game_mines";
        $mineUnit = $sqlQuery->sqlQuery($query);
        if ($baseUnit == [] && $mineUnit == []) {
            return false;
        } else {
            $units["base"] = [];
            foreach ($baseUnit as $base) {
                $units["base"][$base["id"]]["workers"] = $base["workers"];
                $units["base"][$base["id"]]["soldiers"] = $base["soldiers"];
            }
            $units["mine"] = [];
            foreach ($mineUnit as $base) {
                $units["mine"][$base["id"]]["workers"] = $base["workers"];
                $units["mine"][$base["id"]]["soldiers"] = $base["soldiers"];
            }
            return $units;
        }
    }

    public function buySpace($type, $origin, $amount=5)
    {
        $arr = explode(",", $origin);
        $originType = $arr[0];
        $originId = $arr[1];
        $space = $this->getSpace($type, $originType, $originId);
        $space = $space + $amount;
        $DBConnection = $this->getDBConnection();
        if ($originType === "base"){
            if ($type === "worker"){
                $statement = "UPDATE game_bases SET workerSpace = :space WHERE id = :originId";
            } elseif ($type === "soldier"){
                $statement = "UPDATE game_bases SET soldierSpace = :space WHERE id = :originId";
            } else {
                return false;
                die();
            }
        } elseif ($originType === "mine"){
            if ($type === "worker"){
                $statement = "UPDATE game_mines SET workerSpace = :space WHERE id = :originId";
            } elseif ($type === "soldier"){
                $statement = "UPDATE game_mines SET soldierSpace = :space WHERE id = :originId";
            } else {
                return false;
                die();
            }
        } else {
            return false;
            die();
        }
        $query = $DBConnection->prepare($statement);
        $query->bindparam(":space", $space, PDO::PARAM_INT);
        $query->bindparam(":originId", $originId, PDO::PARAM_INT);
        $query->execute();
    } 

    public function getSpace($type, $buildingType, $id)
    {
        $DBConnection = $this->getDBConnection();
        if ($buildingType === "base"){
            if ($type === "worker"){
                $statement = "SELECT workerSpace FROM game_bases WHERE id = :id";
            } elseif ($type === "soldier"){
                $statement = "SELECT soldierSpace FROM game_bases WHERE id = :id";
            } else {
                return false;
                die();
            }
        } elseif ($buildingType === "mine"){
            if ($type === "worker"){
                $statement = "SELECT workerSpace FROM game_mines WHERE id = :id";
            } elseif ($type === "soldier"){
                $statement = "SELECT soldierSpace FROM game_mines WHERE id = :id";
            } else {
                return false;
                die();
            }
        } else {
            return false;
            die();
        }
        var_dump($statement);
        $id = (int)$id;
        var_dump($id);
        $query = $DBConnection->prepare($statement);
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
        $space = $query->fetch();
        var_dump($space);
        return $space[0];
    }

}
    
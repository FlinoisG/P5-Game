<?php

namespace App\Repository;

use PDO;
use App\Model\Repository;
use App\Entity\BaseEntity;
use App\Service\sqlQueryService;

class BuildingRepository extends Repository
{

    public function getPlayerId($buildingId, $buildingType)
    {
        $DBConnection = $this->getDBConnection();
        if (is_null($buildingType)){
            $buildingType = $this->getType();
        }
        if ($buildingType === "base") {
            $statement = "SELECT playerId FROM game_bases WHERE id= :id";
        } else if ($buildingType === "mine") {
            $statement = "SELECT playerId FROM game_mines WHERE id= :id";
        } else {
            return false;
            die();
        }
        $query = $DBConnection->prepare($statement);
        $query->bindParam(':id', $buildingId, PDO::PARAM_INT);
        $query->execute();
        $response = $query->fetch();
        return $response[0];
    }


    /**
     * Adds units in a base/mine from the database
     *
     * @param string $unitType Worker or soldier
     * @param mixed $id Id of the base/mine
     * @param integer $amount amount of unit to add (can be begative)
     * @param string $buildingType Type of building (base/mine)
     * @return void
     */
    public function addUnits($unitType, $id, $amount = 1, $buildingType = null)
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

    /**
     * Get available free space inside a building
     *
     * @param string $unitType worker or soldier
     * @param mixed $id Id of the building
     * @param string $buildingType Type of building (base/mine)
     * @return void
     */
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

    /**
     * Get the number of worker or soldier unit in a building
     *
     * @param string $unitType worker or soldier
     * @param mixed $id Id of the building
     * @param string $buildingType Type of building (base/mine)
     * @return void
     */
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

    /**
     * Get the position of a building
     *
     * @param int $id Id of the building
     * @param string $buildingType base or mine, type of building
     * @return void
     */
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

    /**
     * Get all units form all buildings
     *
     * @return array
     */
    public function getAllUnits()
    {
        $sqlQueryService = new sqlQueryService();
        $query = "SELECT id, workers, soldiers FROM game_bases";
        $baseUnit = $sqlQueryService->sqlQueryService($query);
        $query = "SELECT id, workers, soldiers FROM game_mines";
        $mineUnit = $sqlQueryService->sqlQueryService($query);
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

    public function getHP($id, $buildingType)
    {
        $DBConnection = $this->getDBConnection();
        if ($buildingType === "base") {
            $statement = "SELECT HP FROM game_bases WHERE id= :id";
        } else if ($buildingType === "mine") {
            $statement = "SELECT HP FROM game_mines WHERE id= :id";
        } else {
            return false;
            die();
        }
        $query = $DBConnection->prepare($statement);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $buildingHP = $query->fetch();
        return $buildingHP[0];
    }

    public function setHP($buildingHP, $id, $buildingType)
    {
        $DBConnection = $this->getDBConnection();
        if ($buildingType === "base") {
            $statement = "UPDATE game_bases SET HP = :buildingHP WHERE id= :id";
        } else if ($buildingType === "mine") {
            $statement = "UPDATE game_mines SET HP = :buildingHP WHERE id= :id";
        } else {
            return false;
            die();
        }
        $query = $DBConnection->prepare($statement);
        $query->bindParam(':buildingHP', $buildingHP, PDO::PARAM_INT);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * Upgrade a building by adding space of a certain type
     *
     * @param string $unitType
     * @param string $buildingType
     * @param mixed $buildingId
     * @param integer $amount
     * @return void
     */
    public function addSpace($unitType, $buildingType, $buildingId, $amount=5)
    {
        //$arr = explode(",", $origin);
        //$originType = $arr[0];
        //$originId = $arr[1];
        $space = $this->getSpace($unitType, $buildingType, $buildingId);
        $space = $space + $amount;
        $DBConnection = $this->getDBConnection();
        if ($buildingType === "base"){
            if ($unitType === "worker"){
                $statement = "UPDATE game_bases SET workerSpace = :space WHERE id = :originId";
            } elseif ($type === "soldier"){
                $statement = "UPDATE game_bases SET soldierSpace = :space WHERE id = :originId";
            } else {
                return false;
                die();
            }
        } elseif ($buildingType === "mine"){
            if ($unitType === "worker"){
                $statement = "UPDATE game_mines SET workerSpace = :space WHERE id = :originId";
            } elseif ($unitType === "soldier"){
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
        $query->bindparam(":originId", $buildingId, PDO::PARAM_INT);
        $query->execute();
    } 

    /**
     * Get workerSpace or soldierSpace from a building
     *
     * @param string $type worker or soldier
     * @param string $buildingType base or mine
     * @param mixed $id id of the building
     * @return int
     */
    public function getSpace($unitType, $buildingType, $buildingId)
    {
        $DBConnection = $this->getDBConnection();
        if ($buildingType === "base"){
            if ($unitType === "worker"){
                $statement = "SELECT workerSpace FROM game_bases WHERE id = :id";
            } elseif ($unitType === "soldier"){
                $statement = "SELECT soldierSpace FROM game_bases WHERE id = :id";
            } else {
                return false;
                die();
            }
        } elseif ($buildingType === "mine"){
            if ($unitType === "worker"){
                $statement = "SELECT workerSpace FROM game_mines WHERE id = :id";
            } elseif ($unitType === "soldier"){
                $statement = "SELECT soldierSpace FROM game_mines WHERE id = :id";
            } else {
                return false;
                die();
            }
        } else {
            return false;
            die();
        }
        //$id = (int)$id;
        $query = $DBConnection->prepare($statement);
        $query->bindParam(":id", $buildingId, PDO::PARAM_INT);
        $query->execute();
        $space = $query->fetch();
        return $space[0];
    }

    /**
     * Get the user name of a building's owner
     *
     * @param string $type base or mine
     * @param mixed $id id of the building
     * @return string owner's username
     */
    public function getOwnerUsername($type, $id)
    {
        $DBConnection = $this->getDBConnection();
        if (!ctype_digit($id)) {
            return false;
            die();
        }
        if ($type === "base"){
            $statement = "SELECT player FROM game_bases WHERE id = :id";
        } elseif ($type === "mine"){
            $statement = "SELECT player FROM game_mines WHERE id = :id";
        } else {
            return false;
            die();
        }
        $query = $DBConnection->prepare($statement);
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
        $pos = $query->fetch();
        return $pos['player'];
    }

    public function setOwner($baseId, $buildingType, $newUserId)
    {
        $userRepository = new UserRepository;
        $newUsername = $userRepository->getUsernameWithId($newUserId);
        $DBConnection = $this->getDBConnection();
        if ($buildingType === "base") {
            $statement = "UPDATE game_bases SET player = :newUsername, playerId = :newUserId WHERE id= :id";
        } else if ($buildingType === "mine") {
            $statement = "UPDATE game_mines SET player = :newUsername, playerId = :newUserId WHERE id= :id";
        } else {
            return false;
            die();
        }
        
        $query = $DBConnection->prepare($statement);
        $query->bindParam(':newUsername', $newUsername, PDO::PARAM_STR);
        $query->bindParam(':newUserId', $newUserId, PDO::PARAM_INT);
        $query->bindParam(':id', $baseId, PDO::PARAM_INT);
        $query->execute();
    }

}
    
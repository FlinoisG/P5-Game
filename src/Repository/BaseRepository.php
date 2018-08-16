<?php

namespace App\Repository;

use PDO;
use App\Model\Repository;
use App\Entity\BaseEntity;

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
    
}

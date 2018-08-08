<?php

namespace App\Repository;

use PDO;
use App\Model\Repository;
use App\Entity\BaseEntity;

class ObjectRepository extends Repository
{

    

    public function getSpaceLeft($type, $id)
    {
        $type = $this->type;
        $DBConnection = $this->getDBConnection();
        //$sqlQuery = new sqlQuery();
        $query = $DBConnection->prepare("SELECT ".$type."Space, ".$type."s FROM game_".$type."s WHERE id='".$id."'");
        $result = $sqlQuery->sqlQuery($query);
        $spaceLeft = ($result[0][$type."Space"] - $result[0][$type."s"]) + 1;
        return $spaceLeft;
    }

}
    /*public function getById($id)
    {
        $DBConnection = $this->getDBConnection();
        $table = 'game_'.$this->getType().'s';
        $query = $DBConnection->prepare("SELECT * FROM game_bases WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $obj = new BaseEntity($query->fetch());
        return $obj;
    }
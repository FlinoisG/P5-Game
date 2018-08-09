<?php

namespace App\Repository;

use PDO;
use App\Model\Repository;
use App\Entity\BaseEntity;

class ObjectRepository extends Repository
{

    public function getSpaceLeft($unitType, $id)
    {
        $type = $this->getType();
        $table = "game_".$type."s";
        $DBConnection = $this->getDBConnection();
        $unitSpace = $unitType."Space";
        $unit = $unitType."s";

        //$query = $DBConnection->prepare("SELECT soldierSpace, soldiers FROM game_bases WHERE id = 1");
        $query = $DBConnection->prepare("SELECT :unitSpace, :unit FROM :table WHERE id = :id");
        $query->bindParam(':unitSpace', $unitSpace, PDO::PARAM_STR);
        $query->bindParam(':unit', $unit, PDO::PARAM_STR);
        $query->bindParam(':table', $table, PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch();
        var_dump($result);
        $spaceLeft = ($result[$unitType."Space"] - $result[$unitType."s"]) + 1;
    }

}
    
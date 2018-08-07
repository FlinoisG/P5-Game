<?php

namespace App\Entity;

use App\Model\Repository;
use App\Entity\BaseEntity;

class BaseRepository extends Repository
{

    public function getById($id)
    {
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT * FROM game_bases WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);

        $query->execute();

        $base = new BaseEntity($query->fetch());
        return $base;
    }

}

<?php

namespace App\Repository;

use PDO;
use App\Model\Repository;
use App\Service\sqlQueryService;
use App\Repository\UserRepository;

class LastScoreRepository extends Repository
{

    /**
     * Get data from game_lastScore and store it into an array, and return that array
     *
     * @return array
     */
    public function getLastWinners()
    {
        $sqlQueryService = new sqlQueryService();
        $lastScores = $sqlQueryService->sqlQueryService("SELECT * FROM game_lastScore");
        $lastScoresArray = [];
        for ($i=0; $i < sizeof($lastScores); $i++) {
            $lastScoresArray[$i] = [
                'place'=>$lastScores[$i]["place"], 
                'playerId'=>$lastScores[$i]["playerId"], 
                'score'=>$lastScores[$i]["score"]
            ];
        }
        return $lastScoresArray;
    }

    /**
     * Set the last three winners and their 
     * score into a specific table
     *
     * @param int $firstId
     * @param int $secondId
     * @param int $thirdId
     * @return void
     */
    public function setLastWinners($firstId, $secondId, $thirdId)
    {
        $userRepository = new UserRepository;

        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("UPDATE game_lastScore SET playerId = :playerId, score = :score WHERE place = :place");

        $firstUserScore = $userRepository->getScore($firstId);
        $secondUserScore = $userRepository->getScore($secondId);
        $thirdUserScore = $userRepository->getScore($thirdId);

        $place = "first";
        $query->bindParam(':playerId', $firstId, PDO::PARAM_STR);
        $query->bindParam(':score', $firstUserScore, PDO::PARAM_INT);
        $query->bindParam(':place', $place, PDO::PARAM_STR);
        $query->execute();

        $place = "second";
        $query->bindParam(':playerId', $secondId, PDO::PARAM_STR);
        $query->bindParam(':score', $secondUserScore, PDO::PARAM_INT);
        $query->bindParam(':place', $place, PDO::PARAM_STR);
        $query->execute();

        $place = "third";
        $query->bindParam(':playerId', $thirdId, PDO::PARAM_STR);
        $query->bindParam(':score', $thirdUserScore, PDO::PARAM_INT);
        $query->bindParam(':place', $place, PDO::PARAM_STR);
        $query->execute();
    }

}
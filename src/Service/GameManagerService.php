<?php

namespace App\Service;

use App\Model\Service;
use App\Repository\BaseRepository;
use App\Repository\MineRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Repository\LastScoreRepository;
use App\Service\RankingService;

class GameManagerService extends Service
{

    /**
     * Generate a globally unique identifier
     *
     * @return string GUID
     */
    public function createNewGame()
    {
        $baseRepository = new BaseRepository;
        $mineRepository = new MineRepository;
        $taskRepository = new TaskRepository;
        $userRepository = new UserRepository;
        $lastScoreRepository = new LastScoreRepository;
        $rankingService = new RankingService;
        $mapGeneratorService = new MapGeneratorService;

        $ranking = $rankingService->getRankingByScore();
        $first = array_keys(array_slice($ranking, 0, 1))[0];
        $second = array_keys(array_slice($ranking, 1, 1))[0];
        $third = array_keys(array_slice($ranking, 2, 1))[0];

        $first = $userRepository->getIdWithUsername($first);
        $second = $userRepository->getIdWithUsername($second);
        $third = $userRepository->getIdWithUsername($third);
        
        $lastScoreRepository->setLastWinners($first, $second, $third);

        $baseRepository->deleteEverything();
        $mineRepository->deleteEverything();
        $taskRepository->deleteEverything();

        $users = $userRepository->getUsers();
        foreach ($users as $user) {
            $userScore = $user->getScore();
            $userId = $user->getId();
            if ($userScore > $user->getBestScore()){
                $userRepository->setBestScore($userId, $userScore);
            }
            $userRepository->setScore($userId, 0);
            $userRepository->changeNewUser($userId, 1);
            $userRepository->setMetal($userId, 2500);
        }

        //$mapGeneratorService->getOreMap();
    }

}

<?php

namespace App\Service;

use App\Model\Service;
use App\Repository\UserRepository;

class RankingService extends Service
{
    
    /**
     * Get RankingByScore from the game_users 
     * table and sort the scores
     *
     * @return array
     */
    public function getRankingByScore()
    {
        $userRepository = new UserRepository;
        $users = $userRepository->getUsersScore();
        arsort($users);
        return $users;
    }

    /**
     * Get RankingByBestScore from the game_users 
     * table and sort the scores
     *
     * @return array
     */
    public function getRankingByBestScore()
    {
        $userRepository = new UserRepository;
        $users = $userRepository->getUsersBestScore();
        arsort($users);
        return $users;
    }

    /**
     * Get RankingByTotalScore from the game_users 
     * table and sort the scores
     *
     * @return array
     */
    public function getRankingByTotalScore()
    {
        $userRepository = new UserRepository;
        $users = $userRepository->getUsersTotalScore();
        arsort($users);
        return $users;
    }

}
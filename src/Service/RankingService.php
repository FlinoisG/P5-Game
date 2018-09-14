<?php

namespace App\Service;

use App\Model\Service;
use App\Repository\UserRepository;

class RankingService extends Service
{
    
    public function getRankingByScore()
    {
        $userRepository = new UserRepository;
        $users = $userRepository->getUsersScore();
        arsort($users);
        return $users;
    }

    public function getRankingByBestScore()
    {
        $userRepository = new UserRepository;
        $users = $userRepository->getUsersBestScore();
        arsort($users);
        return $users;
    }

    public function getRankingByTotalScore()
    {
        $userRepository = new UserRepository;
        $users = $userRepository->getUsersTotalScore();
        arsort($users);
        return $users;
    }

}
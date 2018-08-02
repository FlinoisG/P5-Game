<?php

namespace App\Service;

class SecurityService
{

    public function hash_equals($str1, $str2)
    {
        if (strlen($str1) != strlen($str2)) {
            return false;
        } else {
            $res = $str1 ^ $str2;
            $ret = 0;
            for ($i = strlen($res) - 1; $i >= 0; $i--) {
                $ret |= ord($res[$i]);
            }
            return !$ret;
        }
    }

    public function sanitize($data)
    {
        $sanitizedData = htmlspecialchars($data);
        $bannedWords = [
            "select",
            "update",
            "delete",
            "insert into",
            "create database",
            "alter database",
            "create table",
            "alter table",
            "drop table",
            "create index",
            "drop index"
        ];
        $validated = true;
        foreach ($bannedWords as $word) {
            if (strpos(strToLower($sanitizedData), $word) !== false){
                $validated = false;
            }
        }
        if ($validated){
            return $sanitizedData;
        } else {
            return false;
        }    
    }

    /**
     * Check the validity of a token in case a user forget his password
     *
     * @param string $username
     * @param string $tokenClient
     * @return $users
     */
    public function checkTokenValidity($username, $tokenClient)//SecurityService
    {
        $sqlQuery = new sqlQuery();
        $user = $sqlQuery->sqlQuery("SELECT token FROM game_users WHERE username='".$username."'");
        $tokenServ = $user['0']['token'];
        if ($user != [] && $this->hash_equals($tokenServ, crypt($tokenClient, $tokenServ))) {
            $user = $sqlQuery->sqlQuery("SELECT * FROM game_users WHERE token='".$token."'");
        } else {
            $user = [];
        }
        return $users;
    }

    public function validateUsername($username)
    {
        if (!preg_match('/^[a-zA-Z0-9]{2,26}$/', $username)){
            return false;
        } else {
            return true;
        }
    }

    public function validateEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $available = false;
        }
    }

}
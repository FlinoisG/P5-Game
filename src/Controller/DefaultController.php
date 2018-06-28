<?php

namespace App\Controller;

/**
 * Default controller from which all controllers will extends
 */
class DefaultController
{

    /**
     * @param string $error HTTP status code
     * @return void
     */
    public function error ($errorNo = 500)
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        } 
        $scriptHead = "";
        $scriptBody = "";
        $title = 'Erreur ' . $errorNo;
        switch ($errorNo):
            case 403:
                $errorDesc = "Accès refusé";
                break;
            case 404:
                $errorDesc = "Page introuvable";
                break;
            default:
            $errorDesc = "Erreur interne du serveur";
        endswitch;  
        require('../src/View/ErrorView.php');
    }

    /**
     * if $_GET['p'] is not set, die with a 404 error
     *
     * @return void
     */
    public function checkP ()
    {
        if (!isset($_GET['p'])) {
            die($this->error('404'));
        } else {
            $id = $_GET['p'];
        }
    }

    public function setScript ($getScript)
    {
        $scriptTag = "<script src=\"assets/js/" . $getScript . ".js\"></script>";
        return $scriptTag;
    }

    public function dataValidation($data)
    {
        $bannedCommands = [
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
        $validate = true;
        for ($i = 0; $i < count($bannedCommands); $i++) {
            if (strpos(strtolower($data), $bannedCommands[$i])) {
                $validated = false;
            }
        }
        $data = str_replace("'", "\\'", $data);
        $data = str_replace('"', '\\"', $data);
        if (!$validate) {
            die($this->error("403"));
        } else {
            return $data;
        }
    }

}

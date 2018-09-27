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
        $errorNo = 'Erreur ' . $errorNo;
        require('../src/View/ErrorView.php');
    }

    public function customError ($error)
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $title = 'Erreur';
        $errorNo = "";
        $errorDesc = "Accès refusé";
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

    /*public function setScript ($getScript)
    {
        $scriptTag = "<script src=\"assets/js/" . $getScript . ".js\"></script>";
        return $scriptTag;
    }*/

    public function setCustomStyle ($getStyle)
    {
        $styleTag = "<link href=\"assets/CSS/" . $getStyle . ".css\" rel=\"stylesheet\">";
        return $styleTag;
    }

    /**
     * requires the expired session page 
     * when an user's session is expired
     *
     * @return void
     */
    public function expiredSession()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $title = 'Session expirée';
        require('../src/View/ExpiredSessionView.php');
    }

}

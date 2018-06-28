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
    public function error ($error)
    {
        $errorNo = $error;
        switch ($error):
            case 403:
                $errorDesc = "Accès refusé";
                break;
            case 404:
                $errorDesc = "Page introuvable";
                break;
            case 500:
                $errorDesc = "Erreur interne du serveur";
                break;
            default:
            $errorNo = 500;
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
        $script = "<script src=\"assets/js/" . $getScript . ".js\"></script>";
        return $script;
    }
}

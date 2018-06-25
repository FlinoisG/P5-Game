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
    public function error($error)
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
        require('../src/View/error.php');
    }

    /**
     * if $_GET['params'] is not set, die with a 404 error
     *
     * @return void
     */
    public function checkParams()
    {
        if (!isset($_GET['params'])) {
            die($this->error('404'));
        } else {
            $id = $_GET['params'];
        }
    }
}

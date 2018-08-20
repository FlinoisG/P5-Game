<?php

namespace App\Service;

use App\Controller\DefaultController;
use PDO;

/**
 * used for comunication with the database
 */
class sqlQueryService
{
    private $host;
    private $dbname;
    private $username;
    private $password;

    /**
     * Stores database
     */
    public function __construct()
    {
        $str = file_get_contents(__DIR__.'/../Config/mysqlConfig.json');
        $configs = json_decode($str, true);
        $this->host = $configs['host'];
        $this->dbname = $configs['dbname'];
        $this->username = $configs['username'];
        $this->password = $configs['password'];
    }

    /**
     * Take a sql request and return the result
     *
     * @param string $query
     * @return mixed
     */
    public function sqlQueryService($query)
    {
        
        try {
            
            $bdd = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8', $this->username, $this->password);
            
        } catch (Exeption $e) {
            
            die('Erreur : ' . $e->getMessage());
        }
        
        $query = $bdd->query($query);
        if (!$query) {
            $DefaultController = new DefaultController();
            die($DefaultController->error('500'));
        }
        $result = $query->fetchAll();
        return $result;
    }
}

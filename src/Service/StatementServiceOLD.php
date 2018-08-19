<?php

namespace App\Service;

use PDO;
use App\Model\Service;

class StatementService extends Service
{

    private $host;
    private $dbname;
    private $username;
    private $password;
    private $pdo;

    /**
     * Stores database
     */
    public function __construct()
    {
        $str = file_get_contents(__DIR__.'/mysqlConfig.json');
        $configs = json_decode($str, true);
        $this->host = $configs['host'];
        $this->dbname = $configs['dbname'];
        $this->username = $configs['username'];
        $this->password = $configs['password'];

        try {
            
            $this->$pdo = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8', $this->username, $this->password);
            
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

    public function getIdByUsername($username){
        $sqlQuery = new sqlQuery();
        $query = "SELECT ID FROM game_users WHERE username='".$username."'";
        $result = $sqlQuery->sqlQuery($query);
        return $result[0]['ID'];
    }

    /**
     * Take a sql request and return the result
     *
     * @param string $query
     * @return mixed
     */
    public function sqlQuery($query)
    {
        
        
    }

}
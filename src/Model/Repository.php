<?php

namespace App\Model;

use PDO;

abstract class Repository
{

    private $DBConnection;
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

    protected function getDBConnection()
    {
        
        if ($this->DBConnection === null){
            try {
            
                $this->DBConnection = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8', $this->username, $this->password);
                
            } catch (Exeption $e) {
                
                die('Erreur : ' . $e->getMessage());
            }
        }
        
        return $this->DBConnection;
        
    }

}
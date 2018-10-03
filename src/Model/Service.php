<?php

namespace App\Model;

use PDO;

abstract class Service
{

    private $DBConnection;
    private $host;
    private $dbname;
    private $username;
    private $password;

    /**
     * Get database connection informations 
     * and stores them into itself
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
     * create e new instance od PDO and stores it into itself
     *
     * @return void
     */
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
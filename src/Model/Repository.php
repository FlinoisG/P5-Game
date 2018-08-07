<?php

namespace App\Model;

abstract class Repository
{

    private $DBConnection;

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
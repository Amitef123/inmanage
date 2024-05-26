<?php
    class Database {
        private $serverName;
        private $serverUsername;
        private	$serverPassword;
        private $dbName;
        private $con;
        
        function __construct($sName, $sUsername, $sPassword, $dbN){
            $this->serverName = $sName;
            $this->serverUsername = $sUsername;
            $this->serverPassword = $sPassword;
            $this->dbName = $dbN;
            $this->connect();
        }
        
        private function connect(){
            $this->con = new mysqli($this->serverName, $this->serverUsername, $this->serverPassword, $this->dbName);
            if($this->con->connect_error)
                die("Unsuccessful connection: " . $this->con->connect_error);
        }
        
        //Basic functions from q1

        function select($query){
            $result=$this->con->query($query);
            if(!$result)
                die("Query error: " . $this->con->error);
            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            return $rows;
        }
        
        function insert($query){
            $result=$this->con->query($query);
            if(!$result)
                die("Query error: " . $this->con->error);
            return true;
        }
        
        function update($query){
            $result=$this->con->query($query);
            if(!$result)
                die("Query error: " . $this->con->error);
            return true;
        }
        
        function del($query){ 
            $result=$this->con->query($query);
            if(!$result)
                die("Query error: " . $this->con->error);
            return true;
        }


    }
?>
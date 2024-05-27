<?php
    class Database {
        private $serverName;
        private $serverUsername;
        private	$serverPassword;
        private $dbName;
        private $con;
        private static $postsId=0;
        
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
        
        function create($tableName,$colums){
            $query = "CREATE TABLE IF NOT EXISTS ".$tableName." (";
            foreach($colums as $column){
                $query .= " " . $column['name'] . " " . $column['type'] . " " . (isset($column['extra']) ? $column['extra'] : '') . ",";
            }
            $query = substr($query, 0, -1);
            $query .= ")";
            try{
                $this->con->query($query);
            }catch(Exception $e){
                echo "Error: ".$e->getMessage();
            }
        }

        function insert($tableName,$values){
            $query = "INSERT INTO `".$tableName."` (";
            $queryValues = "VALUES (";
            foreach($values as $key => $value){
                $query .= "`" . $key . "`,";
                $queryValues .= "'" . $value . "',";
            }
            $query = substr($query, 0, -1) . ") ";
            $queryValues = substr($queryValues, 0, -1) . ")";
            $query .= $queryValues;
            try{
                $this->con->query($query);
            }catch(Exception $e){
                echo "Error: ".$e->getMessage();
            }
        }
        function getMaxid($tableName, $idName="id"){
            $query = "SELECT MAX($idName) as maxId FROM `".$tableName."`";
            try{
                $result = $this->con->query($query);
                $row = $result->fetch_assoc();
                return $row['maxId'];
            }catch(Exception $e){
                echo "Error: ".$e->getMessage();
            }
        }

        function select($tableName,$columns,$condition="",$order="", $join = [],$group=""){
            $query = "SELECT ";
            if(count($columns) == 0){
                $query .= "* ";
            }else{
                foreach($columns as $column){
                    $query .= " " . $column . " ,";
                }
                $query = substr($query, 0, -1) . " ";
            }
            $query .= "FROM ".$tableName." ";

            foreach ($join as $joinItem) {
                echo $joinItem['table']."<br>";
                if (isset($joinItem['table']) && isset($joinItem['condition'])) {
                    $joinCondition = is_array($joinItem['condition']) ? implode(" AND ", $joinItem['condition']) : $joinItem['condition'];
                    $query .= "JOIN " . $joinItem['table'] . " ON " . $joinCondition . " ";
                }
            }
            if($condition != ""){
                $query .= "WHERE " . $condition . " ";
            }
            if($order != ""){
                $query .= "ORDER BY " . $order . " ";
            }
            if($group != ""){
                $query .= "GROUP BY " . $group . " ";
            }
            try{
                // echo $query."<br>";
                $result = $this->con->query($query);
                $rows = [];
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
                return $rows;
            }catch(Exception $e){
                echo "Error: ".$e->getMessage();
            }
        }

        function add($tableName,$columnName,$columnType){
            $query = "ALTER TABLE `".$tableName."` ADD `".$columnName."` ".$columnType;
            try{
                $this->con->query($query);
            }catch(Exception $e){
                echo "Error: ".$e->getMessage();
            }
        }

        function update($tableName,$values,$condition){
            $query = "UPDATE `".$tableName."` SET ";
            foreach($values as $key => $value){
                $query .= "`" . $key . "` = '" . $value . "',";
            }
            $query = substr($query, 0, -1) . " ";
            $query .= "WHERE " . $condition;
            try{
                $this->con->query($query);
            }catch(Exception $e){
                echo "Error: ".$e->getMessage();
            }
        }

        function del ($tableName,$condition){
            $query = "DELETE FROM `".$tableName."` WHERE " . $condition;
            try{
                $this->con->query($query);
            }catch(Exception $e){
                echo "Error: ".$e->getMessage();
            }
        }
       
        function close(){
            $this->con->close();
        }

        function columnExists($columnName, $tableName){
            $query = "SHOW COLUMNS FROM `".$tableName."` LIKE '".$columnName."'";
            $result = $this->con->query($query);
            if($result->num_rows > 0)
                return true;
            return false;
        }

        function check(){
            $query="SELECT a*
            FROM posts as a
            JOIN(select userId, max(date) date 
                from posts 
                group by userId) as b
            ON a.userId = b.userId and 
            a.date = b.date;";
            $result = $this->con->query($query);
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
        }
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
    //Execute a SELECT query specified by the provided string.
    function selectByQuery($query){
        $result = $this->con->query($query);
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }


    }
   

?>
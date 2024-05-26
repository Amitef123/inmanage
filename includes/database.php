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

        function createTable($query){
            $result= $this->con->query($query);
            if(!$result)
                die("Query error: " . $this->con->error);
            return true;
        }

        function insertUser($userId,$name, $email, $status){

            if($this->userExists($userId))
                return;
            $query = "INSERT INTO `users` ( `userId` , `name`, `email`, `status`) VALUES ('".$userId."','".$name."','".$email."','".$status."')";
            $this->insert($query);
        }

        function insertPost($id=0,$userId, $title, $content, $status){
            if($id==0&&self::$postsId+1<$id)
                $id=self::$postsId+1;
            if(!$this->userExists($userId) || $this->postsExists($id))
                return;
			$query = "INSERT INTO `posts` (`id`,`userId`, `title`, `content`, `status`) VALUES ('".$id."','".$userId."','".$title."','".$content."','".$status."')";
			if($this->insert($query)){
                self::$postsId++;
            }
		}

        function userExists($userId){
            $query = "SELECT * FROM `users` WHERE `userId` = '".$userId."'";
            $result = $this->select($query);
            if(count($result) > 0)
                return true;
            return false;
        }

        function postsExists($postId){
            $query = "SELECT * FROM `posts` WHERE `id` = '".$postId."'";
            $result = $this->select($query);
            if(count($result) > 0)
                return true;
            return false;
        }

        function showPosts(){
            $query="SELECT * FROM users JOIN posts ON users.userId=posts.userId AND users.status=1 ORDER BY posts.id";
            return $this->select($query);
        }

        function close(){
            $this->con->close();
        }


    }
   

?>
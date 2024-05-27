<?php
    include('./includes/config.php');
    class DBService{
        
        private $db;
        function __construct(){
            global $DB_serverName, $DB_serverUsername, $DB_serverPassword, $DB_dbName;
            $this->db = new Database($DB_serverName, $DB_serverUsername, $DB_serverPassword, $DB_dbName);;
        }
        //Create the tables
        function createTables(){
            $columns = array(
                array("name"=>"userId", "type"=>"INT(9) NOT NULL","extra"=>"PRIMARY KEY"),
                array("name"=>"name", "type"=>"VARCHAR(30) NOT NULL"),
                array("name"=>"email", "type"=>"VARCHAR(100) NOT NULL"),
                array("name"=>"status", "type"=>"INT NOT NULL")
            );
            $this->db->create("users", $columns);
            $columns = array(
                array("name"=>"id", "type"=>"INT NOT NULL AUTO_INCREMENT", "extra"=>"PRIMARY KEY"),
                array("name"=>"userId", "type"=>"INT (9) NOT NULL"),
                array("name"=>"title", "type"=>"VARCHAR(100) NOT NULL"),
                array("name"=>"content", "type"=>"LONGTEXT NOT NULL"),
                array("name"=>"date", "type"=>"TIMESTAMP DEFAULT CURRENT_TIMESTAMP"),
                array("name"=>"status", "type"=>"INT NOT NULL"),
                array("name"=>"FOREIGN KEY (userId)", "type"=>"REFERENCES users(userId)")
            );
            $this->db->create("posts", $columns);
        }
        //Insert a user
        function insertUser($userId, $name, $email, $status){
            $values = array(
                "userId"=>$userId,
                "name"=>$name,
                "email"=>$email,
                "status"=>$status
            );
            if($this->userExists($userId))
                return;
            $this->db->insert("users", $values);
        }
        //Check if a user exists
        function userExists($userId){
            $result=$this->db->select("users", array("userId"), "userId=".$userId, "");
            if(count($result)>0)
                return true;
            return false;
        }
        //Insert a post
        function insertPost($postId, $userId, $title, $content, $status){
            $lastPostId=$this->db->getMaxid("posts");
            if($postId==0||$postId>$lastPostId+1)
                $postId=$lastPostId+1;
            if(!$this->userExists($userId) || $postId<=$lastPostId)
                return;
            $values = array(
                "userId"=>$userId,
                "id"=>$postId,
                "title"=>$title,
                "content"=>$content,
                "status"=>$status
            );
            $this->db->insert("posts", $values);
        }
        //Show all posts
        function showPosts(){
            $join['table']="posts";
            $join["condition"]=array("users.userId=posts.userId","users.status=1");
            $joins=array($join);
            return $this->db->select("users",array(),"","posts.id",$joins);
        }

        //Create a new column in the users table called birthday
        function CreateBirthdayColumn(){
            if(!$this->db->columnExists("birthday","users"))
                $this->db->add("users", "birthday", "DATE");
        }
        //Add a birthday date to a user
        function addBirthdayDate($userId,$birthday){
            if(!$this->db->columnExists("birthday","users"))
                $this->CreateBirthdayColumn();
            $this->db->update("users",array("birthday"=>$birthday),"userId=".$userId);
        }
        //Set a random birthday date for all users
        function usersSetBirthday(){
            if(!$this->db->columnExists("birthday","users"))
                $this->CreateBirthdayColumn();
            $users=$this->db->select("users",array("userId","birthday"));
            foreach($users as $user){
                if(isset($user['birthday']))
                        continue;
                $birthday=sprintf('%04d-%02d-%02d',rand(1970,2000),rand(1,12),rand(1,31));
                $this->addBirthdayDate($user['userId'],$birthday);
            }
        }
        //Show last post of all users that have a birthday in the current month

        function showBirthdayPosts(){
            $query="
            SELECT 
                rank.*, 
                u.*
            FROM (
                SELECT
                p.*,
                ROW_NUMBER() OVER (PARTITION BY p.userId ORDER BY p.date DESC, p.id DESC)rank
                from 
                posts p
            )rank
            JOIN 
                users u ON rank.userId = u.userId
            WHERE 
                rank.rank = 1
                AND MONTH(u.birthday) = MONTH(CURDATE())";
            return $this->db->selectByQuery($query);
        }
        //Show the number of posts per day and hour
        function summeryPosts(){
            $tableName="posts";
            $columns=array("DATE(date) AS datePost","HOUR(date) AS hourPost","COUNT(*) AS postsCount");
            $group="datePost, hourPost";
            return $this->db->select($tableName,$columns,"","",[],$group);
        }
        
        function close(){
            $this->db->close();
        }




    }
?>
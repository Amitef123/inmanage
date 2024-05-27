<?php
    include('./includes/config.php');
    class DBService{
        
        private $db;
        function __construct(){
            global $DB_serverName, $DB_serverUsername, $DB_serverPassword, $DB_dbName;
            $this->db = new Database($DB_serverName, $DB_serverUsername, $DB_serverPassword, $DB_dbName);;
        }
        function createTables(){
            $columns = array(
                array("name"=>"id", "type"=>"INT NOT NULL AUTO_INCREMENT", "extra"=>"PRIMARY KEY"),
                array("name"=>"userId", "type"=>"INT(9) NOT NULL"),
                array("name"=>"name", "type"=>"VARCHAR(30) NOT NULL"),
                array("name"=>"email", "type"=>"VARCHAR(100) NOT NULL"),
                array("name"=>"status", "type"=>"INT NOT NULL")
            );
            $this->db->create("users", $columns);
            $columns = array(
                array("name"=>"id", "type"=>"INT NOT NULL AUTO_INCREMENT", "extra"=>"PRIMARY KEY"),
                array("name"=>"userId", "type"=>"INT NOT NULL"),
                array("name"=>"title", "type"=>"VARCHAR(100) NOT NULL"),
                array("name"=>"content", "type"=>"LONGTEXT NOT NULL"),
                array("name"=>"date", "type"=>"TIMESTAMP DEFAULT CURRENT_TIMESTAMP"),
                array("name"=>"status", "type"=>"INT NOT NULL")
            );
            $this->db->create("posts", $columns);
        }

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

        function userExists($userId){
            $result=$this->db->select("users", array("id"), "userId=".$userId, "");
            if(count($result)>0)
                return true;
            return false;
        }

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

        function showPosts(){
            $join['table']="posts";
            $join["condition"]=array("users.userId=posts.userId","users.status=1");
            $joins=array($join);
            return $this->db->select("users",array(),"","posts.id",$joins);
        }
        function CreateBirthdayColumn(){
            if(!$this->db->columnExists("birthday","users"))
                $this->db->add("users", "birthday", "DATE");
        }

        function addBirthdayDate($userId,$birthday){
            if(!$this->db->columnExists("birthday","users"))
                $this->CreateBirthdayColumn();
            $this->db->update("users",array("birthday"=>$birthday),"userId=".$userId);
        }

        function usersSetBirthday(){
            $users=$this->db->select("users",array("userId","birthday"));
            foreach($users as $user){
                if(isset($user['birthday']))
                        continue;
                $birthday=sprintf('%04d-%02d-%02d',rand(1970,2000),rand(1,12),rand(1,31));
                $this->addBirthdayDate($user['userId'],$birthday);
            }
        }

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




    }
?>
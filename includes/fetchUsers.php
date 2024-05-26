<?php
    include('./includes/config.php');
	include('./includes/database.php');
    
    $db =new Database($DB_serverName, $DB_serverUsername, $DB_serverPassword, $DB_dbName);

    $usersData=getDataFromSite($USERS_URL);
    $postsData=getDataFromSite($POSTS_URL);
    createTables($db);

    foreach($usersData as $user){
        $db->insertUser($user['id'],$user['name'], $user['email'], 1);
    }

    foreach($postsData as $post){
        $db->insertPost($post['id'],$post['userId'], $post['title'], $post['body'], 1);
    }



    function getDataFromSite($urlAddress){
        $curl = curl_init($urlAddress);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);

        if(curl_errno($curl)){
            echo 'Error: '.curl_error($curl);
        }
        curl_close($curl);
        return json_decode($result, true);
    }

    function createTables($db){
        $userSql = "CREATE TABLE IF NOT EXISTS `users` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `userId` INT(9) NOT NULL,
            `name` VARCHAR(30) NOT NULL,
            `email` VARCHAR(100) NOT NULL,
            `status` INT NOT NULL,
            PRIMARY KEY (`id`)
        )";
        $db->createTable($userSql);

        $postSql = "CREATE TABLE IF NOT EXISTS `posts` (
            `id` INT NOT NULL AUTO_INCREMENT , 
            `userId` INT NOT NULL , 
            `title` VARCHAR(100) NOT NULL , 
            `content` LONGTEXT NOT NULL ,
            `date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP , 
            `status` INT NOT NULL , 
            PRIMARY KEY (`id`))";
        $db->createTable($postSql);
    }
?>
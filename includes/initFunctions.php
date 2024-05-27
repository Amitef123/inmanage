<?php
    include('./includes/dbService.php');
    
    $service = new DBService();
    $usersData=getDataFromSite($USERS_URL);
    $postsData=getDataFromSite($POSTS_URL);
    $service->createTables();


    foreach($usersData as $user){
        $service->insertUser($user['id'],$user['name'], $user['email'], 1);
    }

    foreach($postsData as $post){
        $service->insertPost($post['id'],$post['userId'], $post['title'], $post['body'], 1);
    }

    include('./includes/fetchPhoto.php');

    $service->usersSetBirthday();


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
    $service->close();


?>
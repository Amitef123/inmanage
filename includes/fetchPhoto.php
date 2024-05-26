<?php

    $photoData=file_get_contents($PHOTO_URL);
    if(!$photoData){
        die("Error fetching photo");
    }
    
    if(!file_put_contents(__DIR__."/../images/profilePic.jpg", $photoData)){
        die ("Error saving photo");
    }
?>
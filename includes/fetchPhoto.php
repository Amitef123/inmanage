<?php
    // Fetch photo from URL
    $photoData=file_get_contents($PHOTO_URL);
    if(!$photoData){
        die("Error fetching photo");
    }
    
    if(!file_put_contents(__DIR__."/../$PHOTO_PATH", $photoData)){
        die ("Error saving photo");
    }
?>
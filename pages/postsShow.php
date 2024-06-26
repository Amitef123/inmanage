<?php
    $service=new DBService();
    $posts = $service->showPosts();
    if(count($posts)==0){
        echo "No posts found";
        return;
    }
    $photoPath=__DIR__."/../".$PHOTO_PATH;
    foreach($posts as $post){

        echo "<div class='post'>";
        echo "<div class='userHeader'>";
        echo "<img class=profilePic src='".$PHOTO_URL."' alt='Profile Picture'>";
        echo "<h4 class=name>".$post['name']."</h4>";
        echo "</div>";
        echo "<h3 class= title>".$post['title']."</h3>";
        echo "<p class=text>".$post['content']."</p>";
        echo "<p>Date: ".$post['date']."</p>";
        echo "</div>";
    }
    $service->close();
?>
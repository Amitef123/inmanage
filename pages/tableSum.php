<?php
$service=new DBService();
$summery = $service->summeryPosts();
 echo "<table>";
 echo "<tr><th>Date</th><th>Hour</th><th>Total Sum</th></tr>";
 foreach($summery as $sum){

        echo "<tr>";
        echo "<td>".$sum['datePost']."</td>";
        echo "<td>".$sum['hourPost'].":00</td>";
        echo "<td>".$sum['postsCount']."</td>";
        echo "</tr>";
 }
    echo "</table>";
$service->close();
?>
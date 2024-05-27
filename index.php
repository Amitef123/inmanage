<?php
	//error_reporting(0);
	include('./includes/config.php');
	include('./includes/database.php');
	include('./includes/initFunctions.php');
	
	
	//$fetchUsers->fetchUsers();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Inmanage</title>
		<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	</head>
	<body class=body>
		<h1>Inmanage</h1>
		<div class="tab-container">
 		 <div class="tab-buttons">
    	<a href="?tab=1" class="tab-button">All posts</a>
    	<a href="?tab=2" class="tab-button">Birthday</a>
  	</div>
  	<div class="container">
    <?php
    if (isset($_GET['tab'])) {
      $tab = $_GET['tab'];
    } else {
      $tab = 1;
    }

    switch ($tab) {
      case 1:
        include './pages/postsShow.php';
        break;
      case 2:
        include './pages/birthdayPosts.php';
        break;
   
      default:
        echo 'Invalid tab';
    }
    ?>
  </div>
</div>
	</body>
</html>
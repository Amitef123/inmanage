<?php
	//error_reporting(0);
	include('./includes/config.php');
	include('./includes/database.php');
	include('./includes/fetchUsers.php');
	include('./includes/fetchPhoto.php');
	
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
		<h1>Posts</h1>
		<div class="container">
		<?php
		include('./pages/postsShow.php');
		
		?>
		</div>
	</body>
</html>
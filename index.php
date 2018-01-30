<?php
	include("functions.php");
	include ("views/header.php");

	if(isset($_GET['page'])) {
		
		if($_GET['page'] == 'feed') {

			include ('views/feed.php');

		} else if($_GET['page'] == 'yourPosts') {

			include ('views/yourPosts.php');

		} else if($_GET['page'] == 'search') {

			include ('views/search.php');

		} else if($_GET['page'] == 'publicProfiles') {

			include ('views/publicProfiles.php');
		}

	} else {

		include ("views/home.php");
	}

	include ("views/footer.php");
?>
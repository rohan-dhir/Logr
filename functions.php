<?php
	
	/*
	* Connects to database and begins session
	* Generates html content to be displayed on each page
	*/
	session_start();

	//Assign database to variable
	$link = mysqli_connect("localhost", "username", "somePassword", "dbName");

	//Stop if error occurs
	if(mysqli_connect_errno()) {
		print_r(mysqli_connect_error());
		exit();
	}

	if(isset($_GET['function'])) {

		//Stop session if user logs out
		if ($_GET['function'] == "logout") {
			session_unset();
		}
	}

	//Establish differences in time from seconds to years
	function time_since($since) {
        $chunks = array(
            array(60 * 60 * 24 * 365 , 'year'),
            array(60 * 60 * 24 * 30 , 'month'),
            array(60 * 60 * 24 * 7, 'week'),
            array(60 * 60 * 24 , 'day'),
            array(60 * 60 , 'hour'),
            array(60 , 'min'),
            array(1 , 'sec')
        );

        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];
            if (($count = floor($since / $seconds)) != 0) {
                break;
            }
        }

        $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
        return $print;
    }

	//Displays posts for respective page
	function displayPosts($type) {

		global $link;

		if($type == 'public') { //Checks if user is on the public page

			$whereClause =""; //Create blank string to be used later when checking database

		} else if ($type == 'isFollowing') { //Checks if user is on the followings page

			//Select all followings from the isFollowing table in database
			$query = "SELECT * FROM isFollowing WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id']);
            $result = mysqli_query($link, $query);

            $whereClause = "";
          
          //Assign whereClause depending on condition
            while ($row = mysqli_fetch_assoc($result)) {
            	if ($whereClause == "") $whereClause = "WHERE";
            	else $whereClause.= " OR";
            	$whereClause.= " userid = ".$row['isFollowing']; //Assign string to userID of those being followed
            }

		} else if ($type == 'yourPosts') { //Checks if user is on their personal page
            
           $whereClause = "WHERE userid = ". mysqli_real_escape_string($link, $_SESSION['id']); //Assign string to indicate userID
            
        } else if ($type == 'search') { //Checks if user is on the search page
            
            echo '<p>Showing search results for "'.mysqli_real_escape_string($link, $_GET['q']).'"</p>'; 
            
           $whereClause = "WHERE text LIKE '%". mysqli_real_escape_string($link, $_GET['q'])."%'"; //Assign string to indicate search query
            
        } else if (is_numeric($type)) { //Checks if user is on friend's page
            
            //Search users table from database for all posts from user's page
            $userQuery = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link, $type)." LIMIT 1";
                $userQueryResult = mysqli_query($link, $userQuery);
                $user = mysqli_fetch_assoc($userQueryResult);
            
            echo "<h2>".mysqli_real_escape_string($link, $user['email'])."'s Posts</h2>";
            
            $whereClause = "WHERE userid = ". mysqli_real_escape_string($link, $type); //Assign string to indicate friend's userID
        }

        //Query database based upon whereClause string's value
		$query = "SELECT * FROM posts ".$whereClause." ORDER BY `datetime` DESC LIMIT 10";
		
		$result = mysqli_query($link, $query);

		//If query is null then there are no posts to display
		if(!$result || mysqli_num_rows($result) == 0) {
			echo "There are no posts to display.";

		} else {

			//Display each post in descending order
			while ($row = mysqli_fetch_assoc($result)) {

				//Fetch userID from each postt to display username
				$userQuery = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link, $row['userid']) ." LIMIT 1";
				$userQueryResult = mysqli_query($link, $userQuery);
				$user = mysqli_fetch_assoc($userQueryResult);

				//Generate a post for each user with time since posted 
				echo "<div class='post'><p><a id='userName' href='?page=publicProfiles&userid=".$user['id'] ."'>" .$user['email'] ."</a> <span class='time'>".time_since(time() - strtotime($row['datetime']))." ago </span> 
				</p>";

				echo "<p>" .$row['text'] ."</p>";

				echo "<p><a class='toggleFollow' id='follow' data-userId='".$row['userid']."'>";

               if(isset($_SESSION['id'])) {

               	//Check if post is from user that is being followed
                 $isFollowingQuery = "SELECT * FROM isFollowing WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id'])." AND isFollowing = ". mysqli_real_escape_string($link, $row['userid'])." LIMIT 1";
                
                //Display Follow/Unfollow link depending on relationship
            	$isFollowingQueryResult = mysqli_query($link, $isFollowingQuery);

            	if($row['userid'] != ($_SESSION['id'])) {

           		 if (mysqli_num_rows($isFollowingQueryResult) > 0) {
             	
                	echo "Unfollow";
                
            	} else {
                
              	 	echo "Follow";
                
           		 } }

           		 if ($row['userid'] == ($_SESSION['id'])) {

           		 	echo "<p><a class='deletePost' id='delete' data-id='" .$row['id'] ."'>Delete</a></p>";
           		 }

           		} else {

           		 	//Do Nothing.
           		 }
                 
                echo "</a></p></div>";
			}

		}
	}

	//Display search box on right side of page
	function displaySearch() {
		if(isset($_SESSION['id'])) {
			 echo '<form class="form-inline">
  					<div class="form-group">
					<input type="hidden" name="page" value="search">
				    <input type="text" name="q" class="form-control-plaintext" id="search" placeholder="Search">
				  </div>
				  <button type="submit" class="btn btn-primary searchPostButton">Search Posts</button>
				</form>';
		}
	}

	//Display post box on right side of page if logged in 
	function displayPostBox() {
		if(isset($_SESSION['id'])) {
			echo '<div id="postSuccess" class="alert alert-success">Posted Successfully.</div>
			<div id="postFail" class="alert alert-danger"></div>
			<div class="form">
 				 <div class="form-group mb-2">
   					 <textarea class="form-control-plaintext" id="postContent"></textarea>
  				</div>
  				<button class="btn btn-primary mb-2 searchPostButton" id="postButton">Post</button>
				</div>';
		}
	}

	//Display usernames on 'Find Friends' Page
	function displayUsers() {

		global $link;

		$query = "SELECT * FROM users LIMIT 10";
		
		$result = mysqli_query($link, $query);

		while ($row = mysqli_fetch_assoc($result)) {

			echo "<p><a id='userName' href='?page=publicProfiles&userid=".$row['id']."'>".$row['email']."</a></p>";
		}
	}

?>
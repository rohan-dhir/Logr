<?php

	include ("functions.php");

/* 
* Get actions called upon from footer.php
* Query the database for respective entries
* Compare/Insert/Update/Delete entries 
* Return value to footer.php indicating success or failure
*/
	//Check if action called is for login/signup 
	if($_GET['action'] == "loginSignUp") {
		$error = ""; //Error string to be used if error occurs

		if(!$_POST['email']) { //Check if email entered is blank
			$error = "An email address is required";

		} else if(!$_POST['password']) {
			$error = "A password is required.";

		}  else if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) { //Check if email entered is valid
  			$error = "Please enter a valid email address.";
}
		//Check if error string is not null
		if ($error != "") {
			echo $error; //Return the error to footer.php
			exit();
		} 
		
		//Check if modal is displaying Signup
		 if ($_POST['loginActive'] == "0") { 
            
            //Query users table from database to check if email already exists
            $query = "SELECT * FROM users WHERE email = '". mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
            $result = mysqli_query($link, $query);

            //Result greater than 0 indicates email already exists in table
            if (mysqli_num_rows($result) > 0) $error = "That email address is already taken.";

            else {
                //Create a new entry with email and password
                $query = "INSERT INTO users (`email`, `password`) VALUES ('". mysqli_real_escape_string($link, $_POST['email'])."', '". mysqli_real_escape_string($link, $_POST['password'])."')";

                if (mysqli_query($link, $query)) {
                    
                    $_SESSION['id'] = mysqli_insert_id($link);

                   //Encrypt user password on the database 
                    $query = "UPDATE users SET password = '". md5(md5($_SESSION['id']).$_POST['password']) ."' WHERE id = ".$_SESSION['id']." LIMIT 1";
                    mysqli_query($link, $query);
                    
                    echo 1; //Returns value to footer.php
                    
                } else {

                    $error = "Couldn't create user - please try again later"; //If error occurs then append to the error string
                }  
            }
           
           //If modal is displaying Login 
        } else {
            
            //Search table for the entered email address
            $query = "SELECT * FROM users WHERE email = '". mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
            
            $result = mysqli_query($link, $query);
            
            $row = mysqli_fetch_assoc($result);
                
                //Compare passwords
                if ($row['password'] == md5(md5($row['id']).$_POST['password'])) {

                    echo 1;   //Return value to footer.php
                    $_SESSION['id'] = $row['id']; //Session ID becomes the user ID
                    
                } else {

                    $error = "Your email address or password is incorrect. Please try again.";
                }
        }
        
        //Return error to footer.php if error string is not empty
         if ($error != "") {
            
            echo $error;
            exit();
            
        }      
        
    }

    //Check if action called is Follow/Unfollow
    if ($_GET['action'] == 'toggleFollow') {
        
        //Search entries from isFollowing table from database 
        $query = "SELECT * FROM isFollowing WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id'])." AND isFollowing = ". mysqli_real_escape_string($link, $_POST['userId'])." LIMIT 1";
            $result = mysqli_query($link, $query);

            //Check if entry exists for following relationship
            if (mysqli_num_rows($result) > 0) { 
                
                $row = mysqli_fetch_assoc($result);
                
                //Unfollow the user if already following
                mysqli_query($link, "DELETE FROM isFollowing WHERE id = ". mysqli_real_escape_string($link, $row['id'])." LIMIT 1");
                
                echo "1";
                  
            } else {

                //Follow the user if no entry existed
                mysqli_query($link, "INSERT INTO isFollowing (follower, isFollowing) VALUES (". mysqli_real_escape_string($link, $_SESSION['id']).", ". mysqli_real_escape_string($link, $_POST['userId']).")");
                
                echo "2";
                
            }
        
    }

    //Action called upon is for making a post
    if ($_GET['action'] == 'postText') {
        
        //Return an error if textarea is blank or post is too long
        if (!$_POST['postContent']) {
                    
            echo "Your post is empty!";
                    
            } else if (strlen($_POST['postContent']) > 140) {
            
            echo "Your post is too long!";
            
        } else {
            
            //Enter post in the posts table in the database
            mysqli_query($link, "INSERT INTO `posts` (`text`, `userid`, `datetime`) VALUES ('". mysqli_real_escape_string($link, $_POST['postContent'])."', ". mysqli_real_escape_string($link, $_SESSION['id']).", NOW())");
            
            echo "1";
            
        }
        
    }

    //Action called upon is for deleting a post
    if ($_GET['action'] == 'deletePost') {
    	
    	//Remove entry from the posts table in the database
    	if(mysqli_query ($link, "DELETE FROM `posts` WHERE id = " .mysqli_real_escape_string($link, $_POST['id'])." LIMIT 1")) echo "1";

    	else echo "Could not delete this post. Try again later."; //Return an error if post could not be deleted
     }

?>
<footer class="footer">
<div class="container">
	<p>&copy; 2018 /Logr/</p>
	</div>

</footer>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel">Login</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="alert alert-danger" id="loginAlert"></div>
        <form>
        <input type="hidden" id="loginActive" name="loginActive" value="1">
  <div class="form-group">
    <label for="email">Email address</label>
    <input type="text" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Email Address">
    <small id="emailHelp" class="form-text text-muted"></small>
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password" placeholder="Password">
  </div>
</form>
      </div>
      <div class="modal-footer">
      	<a id="toggleLogin">Don't have an account?</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="loginSignUpButton">Login</button>
      </div>
    </div>
  </div>
</div>

<script>

//Perfrom function or pass data to actions.php

$("#toggleLogin").click (function() { 
//Changes modal to Login or Signup
	if ($("#loginActive").val() == "1") {

		$("#loginActive").val("0");
		$("#loginModalLabel").html("Sign Up");
		$("#loginSignUpButton").html("Sign Up");
		$("#toggleLogin").html ("Already have an account?");
		$("#emailHelp").html("We'll never share your email with anyone else.");
		$("#stayLoggedIn").html("");
		$("#stayLoggedInCheckBox").hide(true);

	} else {
		$("#loginActive").val("1");
		$("#loginModalLabel").html("Login");
		$("#loginSignUpButton").html("Login");
		$("#toggleLogin").html ("Don't have an account?");
		$("#emailHelp").html("");
		$("#stayLoggedIn").html("Stay Logged In.");
		$("#stayLoggedInCheckBox").hide(false);
	}
})

//Login or signup
 $("#loginSignUpButton").click(function(){

 	//Pass data to actions.php
 	$.ajax({
 		type: "POST",
 		url: "actions.php?action=loginSignUp",
 		data:"email=" + $("#email").val() + "&password=" + $("#password").val() + "&loginActive=" + $("#loginActive").val(),
 		success: function(result) {
 			//if actions.php returned result then perform action
 			if(result == "1") {
 				window.location.assign("http://localhost/SocialMedia_Site/index.php");
 			} else {
 				$("#loginAlert").html(result).show();
 			}
 		}
 	})
 })

//Follow or Unfollow user
 $(".toggleFollow").click(function() {

 	var id = $(this).attr("data-userId"); //Assign userID of user to variable

//Pass data to actions.php
 	$.ajax({
 		type: "POST",
 		url: "actions.php?action=toggleFollow",
 		data:"userId=" + id,
 		success: function(result) {

 			//Follow or unfollow depending on result returned from actions.php
 			if (result == "1") {
 				$("a[data-userid='" + id + "']").html("Follow");
 			} else if (result == "2") {
 				$("a[data-userid='" + id + "']").html("Unfollow");
 			}
 		}
 	})
 })

//Delete a post
 $(".deletePost").click(function() {

 	var content = $(this).attr("data-id");

 	$.ajax({
 		type: "POST",
 		url: "actions.php?action=deletePost",
 		data:"id=" + content,
 		success: function(result) {
 			if (result == "1") {
 				$("#postSuccess").html("Your post has been deleted.").show();
 			} else {
 				$("#postFail").html(result).show();
 			}
 		}
 	})
 })

//Make a post from textarea
 $("#postButton").click(function(){

 	$.ajax({
 		type: "POST",
 		url: "actions.php?action=postText",
 		data:"postContent=" + $("#postContent").val(),
 		success: function(result) {
 			if (result == "1") {
                    
                    $("#postSuccess").show();
                    $("#postFail").hide();
                    
                } else if (result != "") {
                    
                    $("#postFail").html(result).show();
                    $("#postSuccess").hide();
                    
                }
 		}
 	})
 }) 
</script>

  </body>
</html>
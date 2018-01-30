<div class="container-fluid pageContainer">
	<div class="container mainContainer">
		<div class="row">
		  <div class="col-8 homeFeed">

		  	<h2>Feed</h2>
		  	<div class="pre-scrollable" id= "contentSection">
		  	<?php displayPosts('isFollowing'); ?>
		  	</div>

		  </div>
		  <div class="col-4 rightSection">
			<?php displaySearch(); ?>

			<?php displayPostBox(); ?>
			</div>
		</div>
	</div>
</div>


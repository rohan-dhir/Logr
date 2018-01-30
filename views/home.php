<div class="container-fluid pageContainer">
	<div class="container mainContainer">
		<div class="row">
		  <div class="col-8 homeFeed">

			<?php if (isset($_SESSION['id'])) { ?>
		  		<h2>Recent Posts</h2>
		  		<div class="pre-scrollable" id="contentSection">

		  	<?php displayPosts('public'); ?>
		  			
		  		 <?php } else { ?>
		  				<h2>Signup Now and Stay Connected!</h2>
		  				 <div class="pre-scrollable" id="contentSection">
		  		<?php } ?> 
		  	</div>
		  </div>
		  <div class="col-4 rightSection">
			<?php displaySearch(); ?>

			<?php displayPostBox(); ?>
			</div>
		</div>
	</div>
</div>


<div class="container-fluid pageContainer">
	<div class="container mainContainer">

	    <div class="row">
	  <div class="col-md-8 homeFeed">
	      
	        <?php if (isset($_GET['userid'])) {
	        		if ($_GET['userid']) { ?>
					<div class="pre-scrollable" id="contentSection">
	      			<?php displayPosts($_GET['userid']); ?>
	      </div>
	     		 <?php }} else { ?> 
	        
	        			<h2>Active Users</h2>
	       				 <div class="pre-scrollable" id="contentSection">
	       				 <?php displayUsers(); ?>
	      </div>
	     			 <?php } ?>
	     			 
	             </div>

	  <div class="col-md-4">
	        
	        <?php displaySearch(); ?>
	      
	      <?php displayPostBox(); ?>
	        
	        </div>
	</div>
	    
	</div>
	</div>
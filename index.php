<?php 
	@session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <?php 
   		include_once("./include/fonction.php");
   		
   		echo get_head();	
   	?>   
   <body>
   		<div id="wrapper">
   			<div class="under_wrapper">
   				<div id="wrapper_admin">
	   				
   				</div>
   			</div>
   		</div>
   		
   </body>
   <?php echo include_js(); ?>
</html>


<script>
	$(document).ready(function() {
		get_data_admin('tout');
	});


	
</script>

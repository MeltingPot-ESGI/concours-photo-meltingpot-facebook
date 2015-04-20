<?php 
    session_start();
?>
<!doctype html>
<html>
	<head>
      <meta charset="UTF-8">
      <title>Titre de la page</title>
      <meta name="description" content="description de ma page">
    </head>
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

<!doctype html>
<html>
    
    <?php 
   		include_once("../../ressource/include/fonction.php");

        echo get_head();	
    ?>  
    <script>
        window.fbAsyncInit = function() {
          FB.init({
            appId      : '<?php echo APP_ID ?>',
            xfbml      : true,
            version    : 'v2.3'
          });
        };

        (function(d, s, id){
           var js, fjs = d.getElementsByTagName(s)[0];
           if (d.getElementById(id)) {return;}
           js = d.createElement(s); js.id = id;
           js.src = "//connect.facebook.net/fr_FR/sdk.js";
           fjs.parentNode.insertBefore(js, fjs);
         }(document, 'script', 'facebook-jssdk'));
    </script>
    
  
   <body>
        <div id="wrapper">
            <div class="under_wrapper">
                <div id="wrapper_admin">
                    
                    
                    
                    
                    
                    
                    
                    
                    <div id="container_galery">
                    	<a class="jq_magnific" href="../../ressource/public/image/3f2d8b8a.png">Open popup 1</a>
                    	<a class="jq_magnific" href="../../ressource/public/image/8b4d724e76d3f1db6fb504bf3aa29062_h.jpg">Open popup 2</a>
                    	<a class="jq_magnific" href="../../ressource/public/image/62b7528ec90516c621d0ef8f5554fc62_h.jpg">Open popup 3</a>
                    	<a class="jq_magnific" href="../../ressource/public/image/661-femme-tatouee-1219738532-3bead4b.jpg">Open popup 4</a>
                    	<a class="jq_magnific" href="../../ressource/public/image/36095cf22c88d4bb14840336cb015cec_h.jpg">Open popup 5</a>
                    	<a class="jq_magnific" href="../../ressource/public/image/2489757897_1.jpg">Open popup 6</a>
                    	      
                    
                    
                    
                    </div>
	                    
	                <div class="parent-container">
						  <a href="../../ressource/public/image/3f2d8b8a.png">Open popup 1</a>
						  <a href="../../ressource/public/image/8b4d724e76d3f1db6fb504bf3aa29062_h.jpg">Open popup 2</a>
						  <a href="../../ressource/public/image/62b7528ec90516c621d0ef8f5554fc62_h.jpg">Open popup 3</a>
					</div>
	                    
	                
                    <a class="test-popup-link" href="../../ressource/public/image/8b4d724e76d3f1db6fb504bf3aa29062_h.jpg">Open popup</a>
                    
                    
                    
                    
                    
                    
                    
                </div>
            </div>
        </div>
   </body>
</html>


<script>
	$(document).ready(function() {
	  $('.image-link').magnificPopup({type:'image'});
	});
	
    $(document).ready(function() {
/*
		(function(){
			$('#container_galery').on('load','.jq_magnific',function(){
				$(this).magnificPopup({ 
										  type: 'image'
											// other options
										});
			});
		})() 
*/     

		$('.test-popup-link').magnificPopup({ 
		  type: 'image'
			// other options
		});

		$('#container_galery').magnificPopup({
		  delegate: 'a', // child items selector, by clicking on it popup will open
		  type: 'image'
		  // other options
		});
		
		
		
		$('.parent-container').magnificPopup({
		  delegate: 'a', // child items selector, by clicking on it popup will open
		  type: 'image'
		  // other options
		});
        
    });
</script>

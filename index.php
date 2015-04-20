<?php 
	/**
 * FACEBOOK AUTHENTIFICATION
*/
	error_reporting(E_ALL);
    
    require "lib/facebook-php-sdk-v4-4.0-dev/autoload.php";
    
    use Facebook\FacebookSession;
    use Facebook\FacebookRedirectLoginHelper;
    use Facebook\FacebookRequest;
    use Facebook\FacebookRequestException;
    use Facebook\GraphUser;
    
    const APP_ID = "1456178881340751";
    const APP_SECRET = "0a8bd1b0e40a21206aa1b0b02ec14251";
    const REDIRECT_URL = "https://projetadriensyesgi1.herokuapp.com/";
    const FB_TOKEN = 'fb_token';
    
    session_start();
    
    FacebookSession::setDefaultApplication(APP_ID, APP_SECRET);
    
    $loginUrl = "";
    $helper = new FacebookRedirectLoginHelper(REDIRECT_URL);
    
    try {
        $session = $helper->getSessionFromRedirect();
    } catch(FacebookRequestException $ex) {

    } catch(\Exception $ex) {

    }
    
    if ($session) {
        if (isset($_SESSION) && isset($_SESSION[FB_TOKEN])) {
            
        } else {
            $_SESSION[FB_TOKEN] = $session->getAccessToken();
        }
        
        $request = new FacebookRequest( $session, 'GET', '/me' );
        $response = $request->execute();
        
        // Get response
        $graphObject = $response->getGraphObject(GraphUser::className());
    } else {
        $loginUrl = $helper->getLoginUrl();
    }
// ****** Fin FACEBOOK AUTHENTIFICATION **
?>
<!doctype html>
<html>
	<head>
      <meta charset="UTF-8">
      <title>Titre de la page</title>
      <meta name="description" content="description de ma page">
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
    </head>
   <?php 
   		include_once("./include/fonction.php");
   		
   		echo get_head();	
   	?>   
   <body>
   		<?php
            if (isset($graphObject)) {
                echo "Vous êtes connecté en tant que ".$graphObject->getName();
                echo '<img src="http://graph.facebook.com/'.$graphObject->getId().'/picture" alt="Facebook profile picture" height="42" width="42">';
            } else {
                echo '<a class="fb-button" href="'.$loginUrl.'">S\'authentifier avec Facebook</a>';
            }
        ?>
        
        <div
            class="fb-like"
            data-share="true"
            data-width="450"
            data-show-faces="true">
        </div>
		
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

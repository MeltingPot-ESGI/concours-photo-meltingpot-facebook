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
    
    const APP_ID = "342576715932172";
    const APP_SECRET = "f3f97bb62cd6c603fe00128e847586dd";
    const REDIRECT_URL = "https://meltingpot-photo-contest.herokuapp.com/formulaire.php";
    const FB_TOKEN = 'fb_token';
    
    session_start();
    
    FacebookSession::setDefaultApplication(APP_ID, APP_SECRET);
    
    $loginUrl = "";
    $helper = new FacebookRedirectLoginHelper(REDIRECT_URL);
    
    if (isset($_SESSION) && isset($_SESSION[FB_TOKEN])) {
        $session = new FacebookSession($_SESSION[FB_TOKEN]);
    } else {
        try {
            $session = $helper->getSessionFromRedirect();
        } catch(FacebookRequestException $ex) {

        } catch(\Exception $ex) {

        }
    }
    
    if ($session) {
        $_SESSION[FB_TOKEN] = $session->getAccessToken();
        
        $request = new FacebookRequest( $session, 'GET', '/me' );
        $response = $request->execute();
        
        // Get response
        $graphObject = $response->getGraphObject(GraphUser::className());
    } else {
        $loginUrl = $helper->getLoginUrl();
    }
// ****** Fin FACEBOOK AUTHENTIFICATION **
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" media="screen" href="./css/style.css" />
		<title>Formulaire de participation</title>
		<meta name="description" content="Facebook - Concours Photos Tatouages">
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
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
	<body>
		<div class="encart_concours">
			<h1>PARTICIPER AU CONCOURS</h1>
                        
                <?php
                    if (isset($graphObject)) {
                        echo "Vous êtes connecté en tant que ".$graphObject->getName();
                        echo ' <img src="http://graph.facebook.com/'.$graphObject->getId().'/picture" alt="Facebook profile picture" height="42" width="42">';
                    } else {
                        echo '<a id="fb_connect_bt" class="fb-button" href="'.$loginUrl.'">S\'authentifier avec Facebook</a>';
                    }
                ?>

                <!-- <div
                    class="fb-like"
                    data-share="true"
                    data-width="450"
                    data-show-faces="true">
                </div>-->
                        
			<form>
				<input type="text" name="name" value="Nom" id="form_name" size="50" onclick="this.value='';"><br>
				<input type="text" name="email" value="E-mail" id="form_email" size="50" onclick="this.value='';"><br>
				<input type="text" name="city" value="Ville" id="form_city" size="50" onclick="this.value='';"><br>
				<label for="form_gooddeals" class="label_checkbox">Je veux recevoir les bons plans </label><input type="checkbox" name="form_gooddeals" value="" id="form_gooddeals"><br>
				<label for="form_policy" class="label_checkbox">J'accepte <a href="cgu.php">le règlement</a> </label><input type="checkbox" name="form_policy" value="" id="form_reglement">
				<input type="submit" name="form_validate" value="Participer">
			</form>
		</div>
	</body>
</html>
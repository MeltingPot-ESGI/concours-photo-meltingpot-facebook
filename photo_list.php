<?php

require "./ressource/lib/facebook-php-sdk-v4-4.0-dev/autoload.php";
include_once("ressource/include/fonction.php");

error_reporting(E_ALL);
session_start();

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;

FacebookSession::setDefaultApplication(APP_ID, APP_SECRET);

$helper = new FacebookRedirectLoginHelper(REDIRECT_URL);

if (isset($_SESSION) && isset($_SESSION[FB_TOKEN]) && !empty($_SESSION[FB_TOKEN])) {
    $session = new FacebookSession($_SESSION[FB_TOKEN]);
} else {
    try {
        $session = $helper->getSessionFromRedirect();
    } catch(FacebookRequestException $ex) {

    } catch(\Exception $ex) {

    }
}

if ($session) {
die('sd');
    $request = new FacebookRequest(
      $session,
      'GET',
      '/{photo-id}'
    );
    $response = $request->execute();
    $graphObject = $response->getGraphObject();
}

?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Titre de la page</title>
        <meta name="description" content="description de ma page">
        <link rel="stylesheet" type="text/css" media="screen" href="dist/magnific-popup.css" />
        <script type="text/javascript" src="dist/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="dist/jquery.magnific-popup.js"></script>

    </head>
    
    <?php 
   	echo get_head();	
    ?>
    
    <body>
        <div id="wrapper">
            <div class="under_wrapper">
                <div id="wrapper_admin">
                    <div class="encart_concours">
                        <h1>CONCOURS PHOTO TATOUAGE</h1>
                        <div class="parent-container">
                            <a href="image/femme-tatouage-au-bras.jpg" data-mfp-src="image/femme-tatouage-au-bras.jpg" title="<button type='button' onclick='clickMyButton();' >tarte creme </button> penis de vache" ><img src="image/femme-tatouage-au-bras.jpg" title="plume sur tete" border="0" height="50" width="50" ></a>
                            <a href="image/8b4d724e76d3f1db6fb504bf3aa29062_h.jpg" data-mfp-src="image/8b4d724e76d3f1db6fb504bf3aa29062_h.jpg" ><img src="image/8b4d724e76d3f1db6fb504bf3aa29062_h.jpg" border="0" height="50" width="50" ></a>
                            <a href="image/62b7528ec90516c621d0ef8f5554fc62_h.jpg" data-mfp-src="image/62b7528ec90516c621d0ef8f5554fc62_h.jpg" ><img src="image/62b7528ec90516c621d0ef8f5554fc62_h.jpg" border="0" height="50" width="50" ></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>
    <script>

        function clickMyButton(){
            console.log('mon beau button');	

        }
		
        $('.parent-container').magnificPopup({
            delegate: 'a', // child items selector, by clicking on it popup will open
            type: 'image',
            image: {
	           // markup:'<div>toto en string</div>',
            },
	         gallery: {
	             enabled: true, // set to true to enable gallery
	             
	             preload: [0,2], // read about this option in next Lazy-loading section
	             
	             navigateByImgClick: true,
	             
	             arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%">kiri</button>', // markup of an arrow button
	             
	             tPrev: 'Previous (Left arrow key)', // title for left button
	             tNext: 'Next (Right arrow key)', // title for right button
	             tCounter: '<span class="mfp-counter">%curr% of %total%</span>' // markup of counter
	         }
        });

    </script>


<?php echo include_js(); ?>
</html>
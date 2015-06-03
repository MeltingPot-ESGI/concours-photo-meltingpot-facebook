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

// BDD
$dbopts = parse_url(DATA_BASE_URL);

try {
    $pdo = new PDO('pgsql:dbname='.ltrim($dbopts["path"],'/').';host='.$dbopts["host"], $dbopts["user"], $dbopts["pass"]);
} catch (PDOException $e) {
    var_dump($e->getMessage());
}

// Session
if (isset($_SESSION) && isset($_SESSION[FB_TOKEN]) && !empty($_SESSION[FB_TOKEN])) {
    $session = new FacebookSession($_SESSION[FB_TOKEN]);
} else {
    try {
        $session = $helper->getSessionFromRedirect();
    } catch(FacebookRequestException $ex) {

    } catch(\Exception $ex) {

    }
}

?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Titre de la page</title>
        <meta name="description" content="description de ma page">
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
                        <?php
                            if ($session) {
                                $stmt = $pdo->query("SELECT * FROM \"Photos\" ORDER BY date_add DESC LIMIT 15;");
                                
                                while ($photo = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    try {
                                        $request = new FacebookRequest(
                                            $session,
                                            'GET',
                                            '/'.trim($photo['id_facebook'])
                                        );

                                        $response = $request->execute();

                                        $graphObject = $response->getGraphObject();
                                        $images = $graphObject->getProperty('images')->asArray();

                                        $image = $images[0];

                                        $source = $image->source;
                                        
                                        //<button type='button' onclick='clickMyButton();' >tarte creme </button> penis de vache
                                        $title = "<script>
                                    window.fbAsyncInit = function() {
                                      FB.init({
                                        appId      : '".APP_ID."',
                                        xfbml      : true,
                                        version    : 'v2.3'
                                      });
                                    };

                                    (function(d, s, id){
                                       var js, fjs = d.getElementsByTagName(s)[0];
                                       if (d.getElementById(id)) {return;}
                                       js = d.createElement(s); js.id = id;
                                       js.src = '//connect.facebook.net/fr_FR/sdk.js';
                                       fjs.parentNode.insertBefore(js, fjs);
                                     }(document, 'script', 'facebook-jssdk'));
                                </script>"
                                                . "<div class='fb-like' data-href='".$graphObject->getProperty('link')."' data-layout='standard' data-action='like' data-show-faces='true' data-share='true'></div>".$photo['name'];
                        ?>
                                        <a href="<?php echo $source; ?>" data-mfp-src="<?php echo $source; ?>" title="<?php echo $title; ?>" ><img src="<?php echo $source; ?>" title="plume sur tete" border="0" height="50" width="50" ></a>
                        <?php
                                    } catch (Exception $e) {
                                        continue;
                                    }
                                }
                            }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>
    <?php echo include_js(); ?>
    <script>
        $(document).ready(function() {
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
            });

    </script>



</html>
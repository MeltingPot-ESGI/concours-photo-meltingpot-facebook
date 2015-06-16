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
var_dump('0');
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
                                var_dump('62');
                                while ($photo = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    try {
                                        var_dump('65');
                                        $request = new FacebookRequest(
                                            $session,
                                            'GET',
                                            '/'.trim($photo['id_facebook'])
                                        );
var_dump('71');
                                        $response = $request->execute();
var_dump('73');
                                        $graphObject = $response->getGraphObject();
                                        $images = $graphObject->getProperty('images')->asArray();
var_dump('76');
                                        $image = $images[0];
var_dump('78');
                                        $source = $image->source;
var_dump('80');
                                        $title = "
                                <script>
                                alert('ok');
                                    /*document.onload = function() {
                                        alert('ss');
                                        window.fbAsyncInit = function() {
                                          FB.init({
                                            appId      : '342576715932172',
                                            cookie: true,
                                            xfbml      : true,
                                            oauth: true,
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
                                    }*/
                                </script>
                                <div id='fb-root'></div>";
                                        $title .= "<div class='fb-like' data-href='".URL_FOR_LIKE_BUTTON.$graphObject->getProperty('id')."' data-layout='standard' data-action='like' data-show-faces='true' data-share='true' style='height:24px;'></div>".$photo['name'];
                        ?>
                                        <a href="<?php echo $source; ?>" data-mfp-src="<?php echo $source; ?>" onmouseup="alert('tg');" title="<?php echo $title; ?>" ><img src="<?php echo $source; ?>" title="plume sur tete" border="0" height="50" width="50" ></a>
                        <?php
                                    } catch (Exception $e) {
                                        var_dump($e->getMessage());
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
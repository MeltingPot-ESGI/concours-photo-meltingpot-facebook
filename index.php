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
    var_dump("1");
    $session = new FacebookSession($_SESSION[FB_TOKEN]);
} else {
    try {
        var_dump("2");
        $session = $helper->getSessionFromRedirect();
        
        if ($session) {
            $_SESSION[FB_TOKEN] = $session->getAccessToken();
        }
    } catch(FacebookRequestException $ex) {

    } catch(\Exception $ex) {

    }
}

if (!$session) {
    var_dump('3');
    /*$loginUrl = $helper->getLoginUrl(array('scope' => 'publish_actions'));
    
    header("Location: ".$loginUrl);*/
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
                        
                        <a href="participate.php" class="fb-participate-link">Participer au concours</a>
                        
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
                                        
                                        $dataFbHref = URL_FOR_LIKE_BUTTON.$graphObject->getProperty('id');
                                        $dataPhotoName = $photo['name'];
                        ?>
                                    <div class="photo-facebook">
                                        <a href="<?php echo $source; ?>" data-mfp-src="<?php echo $source; ?>"><img src="<?php echo $source; ?>" title="plume sur tete" border="0" height="100" width="100" ></a>
                                        <div id='fb-root'></div>
                                        <p><?php echo $dataPhotoName; ?></p>
                                        <div class='fb-like' data-href='<?php echo $dataFbHref; ?>' data-layout='button_count' data-action='like' data-show-faces='false' data-share='false'></div>
                                    </div>
                        <?php
                                    } catch (Exception $e) {
                                        continue;
                                    }
                                }
                            }
                        ?>
                        </div>
                    <?php
                        if (!$session) {
                            $loginUrl = $helper->getLoginUrl(array('scope' => 'publish_actions'));

                            echo '<a class="fb-button button" href="'.$loginUrl.'">S\'authentifier avec Facebook</a>';
                        }
                    ?>
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
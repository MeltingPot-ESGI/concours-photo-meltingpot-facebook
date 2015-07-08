<?php

require "./ressource/lib/facebook-php-sdk-v4-4.0-dev/autoload.php";
include_once("ressource/include/fonction.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');
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
        
        if ($session) {
            $_SESSION[FB_TOKEN] = $session->getAccessToken();
        }
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
                        
                        
                        <?php
                        
                        // Session
                        $helperParticipate = new FacebookRedirectLoginHelper(REDIRECT_URL_PARTICIPATE);
                        $loginUrlParticipate = $helperParticipate->getLoginUrl(array('scope' => 'publish_actions, user_photos'));
                        
                        ?>
                        
                        <a href="<?php echo $loginUrlParticipate; ?>" class="fb-participate-link fb-form-participate-infos">Participer au concours -></a>
                        
                        <div class="parent-container">
                        <?php
                            if ($session) {
                                $stmtCount = $pdo->query("SELECT COUNT(*) as total_photos FROM \"Photos\";");
                                $result = $stmtCount->fetch(PDO::FETCH_ASSOC);
                                $total = $result['total_photos'];
                                $photosParPage = 6;
                                $nombreDePages = ceil($total / $photosParPage);
                                $pageCourante = isset($_GET['currentPage']) ? (int) $_GET['currentPage'] : 1;
                                
                                $premierePhoto = ($pageCourante-1) * $photosParPage;
                                var_dump("SELECT * FROM \"Photos\" ORDER BY date_add DESC LIMIT ".$premierePhoto.", ".$photosParPage.";");
                                $stmt = $pdo->query("SELECT * FROM \"Photos\" ORDER BY date_add DESC LIMIT ".$premierePhoto.", ".$photosParPage.";");
                                
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
                                        <a class="photo-facebook-js" href="<?php echo $source; ?>" data-mfp-src="<?php echo $source; ?>"><img src="<?php echo $source; ?>" title="plume sur tete" border="0" height="100" width="100" ></a>
                                        <div id='fb-root'></div>
                                        <p><?php echo $dataPhotoName; ?></p>
                                        <div class='fb-like' data-href='<?php echo $dataFbHref; ?>' data-layout='button_count' data-action='like' data-show-faces='false' data-share='false'></div>
                                    </div>
                        <?php
                                    } catch (Exception $e) {
                                        continue;
                                    }
                                }
                                
                                $sHtml = "";
                                
                                if ($nombreDePages > 6) {
                                    $sHtml .= getLinkPage(1);

                                    if ($pageCourante > 1) {
                                            $sHtml .= getLinkPage(($pageCourante-1), '<');
                                    }

                                    $sHtml .= getLinkPage(1);

                                    if ($pageCourante >= ($nombreDePages-2)) {
                                            $sHtml .= "...";
                                            $sHtml .= getLinkPage(($nombreDePages-3));
                                            $sHtml .= getLinkPage(($nombreDePages-2));
                                            $sHtml .= getLinkPage(($nombreDePages-1));
                                    } elseif ($pageCourante > 3 && $pageCourante < ($nombreDePages-2)) {
                                            $pagePrecedente = ($pageCourante-1);
                                            $pageSuivante = ($pageCourante+1);

                                            $sHtml .= "...";
                                            $sHtml .= getLinkPage($pagePrecedente);
                                            $sHtml .= getLinkPage($pageCourante);
                                            $sHtml .= getLinkPage($pageSuivante);
                                            $sHtml .= "...";
                                    } else {
                                            $sHtml .= getLinkPage(2);
                                            $sHtml .= getLinkPage(3);
                                            $sHtml .= getLinkPage(4);
                                            $sHtml .= "...";
                                    } 

                                    $sHtml .= getLinkPage($nombreDePages);

                                    if ($pageCourante < $nombreDePages) {
                                            $sHtml .= getLinkPage(($pageCourante+1), ">");
                                    }

                                    $sHtml .= getLinkPage($nombreDePages, ">>");
                                } elseif ($nombreDePages > 1) {
                                    for ($pageCourante = 1 ; $pageCourante <= $nombreDePages ; $pageCourante++) {
                                            $sHtml .= getLinkPage($pageCourante);
                                    }
                                } else {
                                    $sHtml .= getLinkPage(1);
                                }
                                
                                echo $sHtml;
                            }
                        ?>
                        </div>
                    <?php
                        if (!$session) {
                            $loginUrl = $helper->getLoginUrl();
                            
                            header("Location: ".$loginUrl);
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
                delegate: 'a.photo-facebook-js', // child items selector, by clicking on it popup will open
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
<?php


/**
* FACEBOOK AUTHENTIFICATION
*/
    require "./ressource/lib/facebook-php-sdk-v4-4.0-dev/autoload.php";
    include "src/views/View.php";
    include "src/views/FormParticipateContestView.php";
    
    session_start();
    error_reporting(E_ALL);
    
    
    use Facebook\FacebookSession;
    use Facebook\FacebookRedirectLoginHelper;
    use Facebook\FacebookRequest;
    use Facebook\FacebookRequestException;
    use Facebook\GraphUser;
    
    const APP_ID = "342576715932172";
    const APP_SECRET = "f3f97bb62cd6c603fe00128e847586dd";
    const REDIRECT_URL = "https://meltingpot-photo-contest.herokuapp.com/";
    const FB_TOKEN = 'fb_token';
    const FB_GRAPH_OBJECT = 'fb_graph_object';
    
    FacebookSession::setDefaultApplication(APP_ID, APP_SECRET);
    
    $loginUrl = "";
    $graphObject = null;
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
        $_SESSION[FB_TOKEN] = $session->getAccessToken();
        
        try {
            $request = new FacebookRequest( $session, 'GET', '/me' );
            $response = $request->execute();

            // Get response
            $graphObject = $response->getGraphObject(GraphUser::className());
            
            if (isset($_POST['fileUpload'])) {
                // Upload to a user's profile. The photo will be in the
                // first album in the profile. You can also upload to
                // a specific album by using /ALBUM_ID as the path     
                $response = (new FacebookRequest(
                  $session, 'POST', '/me/photos', array(
                    'source' => new CURLFile($_FILES['photo']['tmp_name'], 'image/png'),
                    'message' => $_POST['photoName']
                  )
                ))->execute()->getGraphObject();

                // If you're not using PHP 5.5 or later, change the file reference to:
                // 'source' => '@/path/to/file.name'

                echo "Posted with id: " . $response->getProperty('id');
            }
        } catch (Exception $e) {
            echo $e->getCode().'--'.$e->getMessage();
        }
    } else {
        $loginUrl = $helper->getLoginUrl(array('scope' => 'publish_actions, user_photos'));
    }
    
// ****** Fin FACEBOOK AUTHENTIFICATION ** //

    /*
    $dbopts = parse_url(getenv('DATABASE_URL'));
    $app->register(new Herrera\Pdo\PdoServiceProvider(),
        array(
            'pdo.dsn' => 'pgsql:dbname='.ltrim($dbopts["path"],'/').';host='.$dbopts["host"],
            'pdo.port' => $dbopts["port"],
            'pdo.username' => $dbopts["user"],
            'pdo.password' => $dbopts["pass"]
        )
    );
    
    $app->get('/db/', function() use($app) {
    $st = $app['pdo']->prepare('SELECT name FROM test_table');
    $st->execute();

    $photos = array();
    while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
        $app['monolog']->addDebug('Row ' . $row['name']);
        $photos[] = $row;
    }

    return $app['twig']->render('database.twig', array(
        'names' => $names
    ));
});*/
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
   	include_once("ressource/include/fonction.php");

        echo get_head();	
    ?>   
   <body>
        <div id="wrapper">
            <div class="under_wrapper">
                <div id="wrapper_admin">
                    <div class="encart_concours">
                        <h1>PARTICIPER AU CONCOURS</h1>
                        <?php
                            if (!empty($graphObject)) {
                                echo "Vous êtes connecté en tant que ".$graphObject->getName();
                                echo ' <img src="http://graph.facebook.com/'.$graphObject->getId().'/picture" alt="Facebook profile picture" height="42" width="42">';
                            } else {
                                echo '<a class="fb-button button" href="'.$loginUrl.'">S\'authentifier avec Facebook</a>';
                            }
                        ?>

                        <form method='post' action="#" enctype="multipart/form-data">
                            <input type="hidden" name="fileUpload" value='1' />
                            <input type="file" name="photo" />
                            <input type="text" name="photoName" value="" />
                            <input type="text" name="name" value="Nom" id="form_name" size="50" onclick="this.value=\'\';"><br>
                            <input type="text" name="email" value="E-mail" id="form_email" size="50" onclick="this.value=\'\';"><br>
                            <input type="text" name="city" value="Ville" id="form_city" size="50" onclick="this.value=\'\';"><br>
                            <div class="form_ligne"><label for="form_gooddeals" class="label_checkbox">Je veux recevoir les bons plans </label><input type="checkbox" name="form_gooddeals" value="" id="form_gooddeals"></div>
                            <div class="form_ligne"><label for="form_policy" class="label_checkbox">J\'accepte <a href="cgu.php">le règlement</a> </label><input type="checkbox" name="form_policy" value="" id="form_reglement"></div>
                            <input type="submit" class="button" name="form_validate" value="Participer">
                      </form>
                    </div>
                </div>
            </div>
        </div>
   </body>
   <?php echo include_js(); ?>
</html>


<script>
    $(document).ready(function() {
      
        var test = <?php echo json_encode(array('loginUrl' => $loginUrl, 'graphObject' => $graphObject)); ?>;
        console.log(test);  
        //get_data_admin('FormParticipateContest', test);
    });
</script>

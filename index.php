<?php
    include_once("/include_config.php");

/**
* FACEBOOK AUTHENTIFICATION
*/
    require "./ressource/lib/facebook-php-sdk-v4-4.0-dev/autoload.php";
    
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
    
    FacebookSession::setDefaultApplication(APP_ID, APP_SECRET);
    
    $loginUrl = "";
    $graphObject = null;
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
                    
                </div>
            </div>
        </div>
   </body>
   <?php echo include_js(); ?>
</html>


<script>
    $(document).ready(function() {
      
        var test = <?php echo json_encode(array('graphObject' => $graphObject, 'loginUrl' => $loginUrl)); ?>;
        console.log(test);  
        get_data_admin('FormParticipateContest', test);
    });
</script>

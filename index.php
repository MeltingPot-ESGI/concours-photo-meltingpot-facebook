<?php
    require "./ressource/lib/facebook-php-sdk-v4-4.0-dev/autoload.php";
    include_once("ressource/include/fonction.php");
    
    error_reporting(E_ALL);
    session_start();
    
    use Facebook\FacebookSession;
    use Facebook\FacebookRedirectLoginHelper;
    use Facebook\FacebookRequest;
    use Facebook\FacebookRequestException;
    use Facebook\GraphUser;
    
    FacebookSession::setDefaultApplication(APP_ID, APP_SECRET);
    
    //Initialisation des variables
    $loginUrl = "";
    $graphObject = null;
    $formErrors = array();
    $successMessage = "Merci pour votre participation ! Votre participation au concours a bien été enregistré.";
    
    // Session
    $helper = new FacebookRedirectLoginHelper(REDIRECT_URL);
    
    // BDD
    $dbopts = parse_url(DATA_BASE_URL);
    
    try {
        $pdo = new PDO('pgsql:dbname='.ltrim($dbopts["path"],'/').';host='.$dbopts["host"], $dbopts["user"], $dbopts["pass"]);
    } catch (PDOException $e) {
        die(var_dump($e->getMessage()));
    }
    
    // Get session
    if (isset($_SESSION) && isset($_SESSION[FB_TOKEN]) && !empty($_SESSION[FB_TOKEN])) {
        $session = new FacebookSession($_SESSION[FB_TOKEN]);
    } else {
        try {
            $session = $helper->getSessionFromRedirect();
        } catch(FacebookRequestException $ex) {

        } catch(\Exception $ex) {

        }
    }
    
    // Récupère les infos de l'utilisateur
    if ($session) {
        $_SESSION[FB_TOKEN] = $session->getAccessToken();
        
        try {
            $request = new FacebookRequest( $session, 'GET', '/me' );
            $response = $request->execute();

            // Get response
            $graphObject = $response->getGraphObject(GraphUser::className());
            
            if (isset($_POST['fileUpload'])) {
                // Vérifie les valeurs
                $_POST['photoName'] = htmlspecialchars($_POST['photoName']);
                $_POST['name'] = htmlspecialchars($_POST['name']);
                $_POST['email'] = htmlspecialchars($_POST['email']);
                $_POST['city'] = htmlspecialchars($_POST['city']);

                if (empty($_POST['photoName']) || empty($_POST['name']) || empty($_POST['email']) || empty($_POST['city'])) {
                    $formErrors[] = "Vous devez remplir tous les champs du formulaire.";
                }

                if (empty($_POST['form_policy'])) {
                    $formErrors[] = "Vous devez accepter le règlement pour pouvoir participer au concours.";
                }

                if (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['email'])) {
                    $formErrors[] = "L'adresse e-mail n'est pas valide.";
                }
                
                if (!isset($_FILES['photo'])) {
                    $formErrors[] = "Veuillez sélectionner un fichier à envoyer.";
                } else if ($_FILES['photo']['size'] <= 0) {
                    $formErrors[] = "Veuillez sélectionner un fichier à envoyer.";
                }
                
                $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
                $detectedType = exif_imagetype($_FILES['fupload']['tmp_name']);
                if (!in_array($detectedType, $allowedTypes)) {
                    $formErrors[] = "Le fichier envoyé n'est pas au bon format. Veuillez envoyer un fichier de type JPEG, PNG ou GIF.";
                }
                
                // Si les valeurs sont valides
                if (0 < count($formErrors)) {
                    // Upload to a user's profile. The photo will be in the
                    // first album in the profile. You can also upload to
                    // a specific album by using /ALBUM_ID as the path     
                    $response = (new FacebookRequest(
                      $session, 'POST', '/me/photos', array(
                        'source' => new CURLFile($_FILES['photo']['tmp_name'], 'image/png'),
                        'message' => $_POST['photoName']
                      )
                    ))->execute()->getGraphObject();

                    $stmt = $pdo->prepare("SELECT * FROM \"Utilisateur\" WHERE id_facebook = :id_facebook;");
                    $stmt->execute(
                        array(':id_facebook' => $graphObject->getId())
                    );

                    // Utilisateur existe dans la BDD
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$user) {
                        // Enregire utilisateur dans la BDD
                        $idFacebook= $graphObject->getId();
                        $firstName = $graphObject->getFirstName();
                        $lastName= $graphObject->getLastName();
                        $email= $_POST['email'];
                        $acceptCgu = isset($_POST['form_policy']);
                        $acceptBonsPlans = isset($_POST['form_gooddeals']);
                        $isEnable = true;

                        $stmt = $pdo->prepare("INSERT INTO \"Utilisateur\" (id_facebook, firstname, lastname, mail, accept_cgu, accept_bons_plans, is_enable) VALUES (:id_facebook, :firstname, :lastname, :mail, :accept_cgu, :accept_bons_plans, :is_enable)");
                        $res = $stmt->execute(
                            array(
                                ':id_facebook' => $idFacebook,
                                ':firstname' => $firstName,
                                ':lastname' => $lastName,
                                ':mail' => $email,
                                ':accept_cgu' => $acceptCgu,
                                ':accept_bons_plans' => $acceptBonsPlans,
                                ':is_enable' => $isEnable,
                            )
                        );

                        $stmt = $pdo->prepare("SELECT * FROM \"Utilisateur\" WHERE id_facebook = :id_facebook;");
                        $stmt->execute(
                            array(':id_facebook' => $graphObject->getId())
                        );

                        // Utilisateur existe dans la BDD
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    }

                    // Enregistre photo dans la BDD
                    $idConcours = 1;
                    $photoIdFacebook = $response->getProperty('id');
                    $idUser = $user['id'];
                    $name = $_POST['name'];
                    $dateAdd = date('Y-m-d H:i:s');
                    $note = 0;

                    $stmt = $pdo->prepare("INSERT INTO \"Photos\" (id_concours, id_user, id_facebook, name, date_add, note) VALUES (:id_concours, :id_user, :id_facebook, :name, :date_add, :note)");
                    $res = $stmt->execute(
                        array(
                            ':id_concours' => $idConcours,
                            ':id_user' => $idUser,
                            ':id_facebook' => $photoIdFacebook,
                            ':name' => $name,
                            ':date_add' => $dateAdd,
                            ':note' => $note,
                        )
                    );
                }
            }
        } catch (Exception $e) {
            echo $formErrors[] = 'Code: '.$e->getCode().' -- Messsage: '.$e->getMessage();
        }
    } elseif (isset($_POST['fileUpload'])) {
        $formErrors[] = "Veuillez vous identifier à facebook pour participer au concours.";
    } else {
        $loginUrl = $helper->getLoginUrl(array('scope' => 'publish_actions, user_photos'));
    }
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
   	echo get_head();	
   ?>   
   <body>
        <div id="wrapper">
            <div class="under_wrapper">
                <div id="wrapper_admin">
                    <div class="encart_concours">
                        <h1>PARTICIPER AU CONCOURS</h1>
                        <?php
                            if (count($formErrors) > 0) {
                                echo '<div class="form-erros">';

                                foreach ($formErrors as $error) {
                                    echo '<span class="form-error">'.$error.'</span><br>';
                                }
                                
                                echo '</div>';
                            } else {
                                echo '<div class="form-success">';
                                
                                echo '<span class="success-message">'.$successMessage.'</span>';
                                
                                echo '</div>';
                            }
                        ?>
                        
                        <?php
                            if (!empty($graphObject)) {
                                echo "Vous êtes connecté en tant que ".$graphObject->getName();
                                echo ' <img src="http://graph.facebook.com/'.$graphObject->getId().'/picture" alt="Facebook profile picture" height="42" width="42">';
                                
                                // GET PHOTO BY ID
                                $request = new FacebookRequest(
                                    $session,
                                    'GET',
                                    '/1405753299745910'
                                  );
                                  $response = $request->execute();
                                  $graphObject = $response->getGraphObject();
                                  
                                echo '<br><br><br><br><img src="'.$graphObject->getProperty('picture').'" alt="Facebook profile picture" height="42" width="42">';
                            } else {
                                echo '<a class="fb-button button" href="'.$loginUrl.'">S\'authentifier avec Facebook</a>';
                            }
                        ?>

                        <form method='post' action="#" enctype="multipart/form-data">
                            <input type="hidden" name="fileUpload" value='1' />
                            <input type="file" name="photo" />
                            <input type="text" name="photoName" value="" />
                            <input type="text" name="name" value="Nom" id="form_name" size="50" onclick="this.value='';"><br>
                            <input type="text" name="email" value="E-mail" id="form_email" size="50" onclick="this.value='';"><br>
                            <input type="text" name="city" value="Ville" id="form_city" size="50" onclick="this.value='';"><br>
                            <div class="form_ligne"><label for="form_gooddeals" class="label_checkbox">Je veux recevoir les bons plans </label><input type="checkbox" name="form_gooddeals" value="1" id="form_gooddeals"></div>
                            <div class="form_ligne"><label for="form_policy" class="label_checkbox">J'accepte <a href="cgu.php">le règlement</a> </label><input type="checkbox" name="form_policy" value="1" id="form_reglement"></div>
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

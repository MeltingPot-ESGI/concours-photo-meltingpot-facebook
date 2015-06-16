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
var_dump('15');
    //Initialisation des variables
    $loginUrl = "";
    $graphObject = null;
    $formErrors = array();
    $successMessage = "Merci pour votre participation ! Votre participation au concours a bien été enregistré.";
    
    // Session
    $helper = new FacebookRedirectLoginHelper(REDIRECT_URL);
  var_dump('24');  
    // BDD
    $dbopts = parse_url(DATA_BASE_URL);
    
    try {
        $pdo = new PDO('pgsql:dbname='.ltrim($dbopts["path"],'/').';host='.$dbopts["host"], $dbopts["user"], $dbopts["pass"]);
    } catch (PDOException $e) {
        var_dump($e->getMessage());
    }
var_dump('33');
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
var_dump('46');    
    // Récupère les infos de l'utilisateur
    if ($session) {
var_dump('49');
        $_SESSION[FB_TOKEN] = $session->getAccessToken();
        
        try {
            $request = new FacebookRequest( $session, 'GET', '/me' );
            $response = $request->execute();
var_dump('55');
            // Get response
            $graphObject = $response->getGraphObject(GraphUser::className());
            $graphObjectUser = $response->getGraphObject();
var_dump('58');        
            if (isset($_POST['fileUpload'])) {
                // Vérifie les valeurs
                $_POST['photoName'] = htmlspecialchars($_POST['photoName']);
var_dump('65');
                if (empty($_POST['photoName'])) {
                    $formErrors[] = "Vous devez remplir tous les champs du formulaire.";
                }

                if (empty($_POST['form_policy'])) {
                    $formErrors[] = "Vous devez accepter le règlement pour pouvoir participer au concours.";
                }

 var_dump('77');               
                if (isset($_POST['fb-photo-id'])) {
                    $fbPhotoId = $_POST['fb-photo-id'];
                } else {
                    if (!isset($_FILES['photo'])) {
                        $formErrors[] = "Veuillez sélectionner un fichier à envoyer.";
                    } else if ($_FILES['photo']['size'] <= 0) {
                        $formErrors[] = "Veuillez sélectionner un fichier à envoyer.";
                    } else if (empty($_FILES['photo']['tmp_name'])) {
                        $formErrors[] = "Le nom du fichier ne peut être vide.";
                    } else {
    var_dump('85');
                        $allowedTypes = array('image/png', 'image/jpeg', 'image/gif');
    var_dump('89');
                        if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
    var_dump('91');
                            $formErrors[] = "Le fichier envoyé n'est pas au bon format. Veuillez envoyer un fichier de type JPEG, PNG ou GIF.";
                        }
                    }
                }
  var_dump('95');              
                // Si les valeurs sont valides
                if (count($formErrors) <= 0) {
                    // Upload to a user's profile. The photo will be in the
                    // first album in the profile. You can also upload to
                    // a specific album by using /ALBUM_ID as the path     
var_dump('0');
                    if (empty($fbPhotoId)) {
                        $response = (new FacebookRequest(
                          $session, 'POST', '/me/photos', array(
                            'source' => new CURLFile($_FILES['photo']['tmp_name'], 'image/png'),
                            'message' => $_POST['photoName']
                          )
                        ))->execute()->getGraphObject();
                    }
var_dump('1');
                    $stmt = $pdo->prepare("SELECT * FROM \"Utilisateur\" WHERE id_facebook = :id_facebook;");
                    
                    $stmt->execute(
                        array(':id_facebook' => $graphObject->getId())
                    );

                    // Utilisateur existe dans la BDD
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
var_dump('2');
                    if (!$user) {
                        // Enregire utilisateur dans la BDD
                        $idFacebook= $graphObject->getId();
                        $firstName = $graphObject->getFirstName();
                        $lastName= $graphObject->getLastName();
                        $email = $graphObjectUser->getProperty('email');
                        $acceptCgu = isset($_POST['form_policy']);
                        $acceptBonsPlans = isset($_POST['form_gooddeals']);
                        $isEnable = true;
var_dump('3');
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
var_dump('4');
                        $stmt = $pdo->prepare("SELECT * FROM \"Utilisateur\" WHERE id_facebook = :id_facebook;");
                        $stmt->execute(
                            array(':id_facebook' => $graphObject->getId())
                        );
var_dump('5');
                        // Utilisateur existe dans la BDD
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    }

                    // Enregistre photo dans la BDD
                    $idConcours = 1;
                    $idUser = $user['id'];
                    $name = $_POST['photoName'];
                    $dateAdd = date('Y-m-d H:i:s');
                    $note = 0;
                    
                    if (empty($fbPhotoId)) {
                        $photoIdFacebook =  $response->getProperty('id');
                    } else {
                        $photoIdFacebook = $fbPhotoId;
                    }
var_dump(array(
                            ':id_concours' => $idConcours,
                            ':id_user' => $idUser,
                            ':id_facebook' => $photoIdFacebook,
                            ':name' => $name,
                            ':date_add' => $dateAdd,
                            ':note' => $note,
                        ));
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
var_dump($res);
                }
            }
        } catch (Exception $e) {
var_dump('error1');
            echo $formErrors[] = 'Code: '.$e->getCode().' -- Messsage: '.$e->getMessage();
        }
    } elseif (isset($_POST['fileUpload'])) {
var_dump('error 2');
        $formErrors[] = "Veuillez vous identifier à facebook pour participer au concours.";
    } else {
var_dump('error 3');
        $loginUrl = $helper->getLoginUrl(array('scope' => 'publish_actions, user_photos'));
    }
    
    if (!empty($graphObject)) {
        $requestAlbums = new FacebookRequest(
        $session,
        'GET',
        '/'. $graphObject->getId().'/albums'
      );
      $responseAlbums = $requestAlbums->execute();
      
      $graphObjectAlbums = $responseAlbums->getGraphObject();
      
      $albums = $graphObjectAlbums->getProperty('data')->asArray();
      $albumsHtml = "";
      
      foreach ($albums as $album) {
          $albumsHtml .= "<img src='http://graph.facebook.com/".$album->cover_photo."'/picture' onclick='clickFbAlbum(".$album->id.");'>"
                  . "<span onclick='clickFbAlbum(".$album->id.");'>".$album->name."</span><br>";
      }
    }
?>

<!doctype html>
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
                        <?php
                            if (!empty($graphObject)) {
                        ?>
                            <div class="fb-profile-picture-block"><img src="http://graph.facebook.com/<?php echo $graphObject->getId(); ?>/picture" class="fb-profile-picture" alt="Facebook profile picture"><?php echo $graphObject->getName(); ?></div>
                        <?php
                            } else {
                                echo '<a class="fb-button button" href="'.$loginUrl.'">S\'authentifier avec Facebook</a>';
                            }
                        ?>
                        
                        <div class="encart-participe-concours">
                            <h1>PARTICIPER AU CONCOURS</h1>

                            <?php
                                if (isset($_POST['fileUpload'])) {
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
                                }
                            ?>

                            <?php
                                if (!empty($graphObject)) {
                            ?>
                            <form method='post' action="#" enctype="multipart/form-data">
                                <input type="hidden" name="fileUpload" value='1' />
                                <table>
                                    <tr>
                                        <td>
                                            <input type="file" name="photo" />
                                        </td>
                                        <td id="fb-albums" class="fb-albums-block">
                                            <?php echo $albumsHtml; ?>
                                        </td>
                                    </tr>
                                </table>

                                <input type="text" name="photoName" value="" />
                                <div class="form_ligne"><label for="form_gooddeals" class="label_checkbox">Je veux recevoir les bons plans </label><input type="checkbox" name="form_gooddeals" value="1" id="form_gooddeals"></div>
                                <div class="form_ligne"><label for="form_policy" class="label_checkbox">J'accepte <a href="cgu.php">le règlement</a> </label><input type="checkbox" name="form_policy" value="1" id="form_reglement"></div>
                                <input type="submit" class="button" name="form_validate" value="Participer">
                            </form>
                            <?php
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
        function clickFbAlbum(id) {
        FB.getLoginStatus(function(response) {
                if (response.authResponse) {
                  //token = response.authResponse.accessToken;
                    FB.api(
                        "/"+id+"/photos",
                        function (response) {
                            if (response && !response.error) {
                                var data = response.data;
                                var photosHtml = "<span onclick='clickReturnAlbums();'>Retour</span><br>";
                                
                                for (i=0, l = data.length; i < l; i++) {
                                    var photo = data[i];
                                    
                                    if ((i%3) == 0) {
                                        photosHtml += "<div>";
                                    }
                                    
                                    photosHtml += "<input type='radio' name='fb-photo-id' value='"+photo.id+"'><img src='"+photo.source+"' alt='Photo facebook' height='50' width='50'>";
                                    
                                    if ((i%3) == 0) {
                                        photosHtml += "</div>";
                                    }
                                }
                                
                                document.getElementById("fb-albums").innerHTML = photosHtml;
                            } else {
                                console.log(response);
                            }
                        }
                    );
                } else {
                  // no user session available, someone you dont know
                }
            });
        }
        
        function clickReturnAlbums() {
            document.getElementById("fb-albums").innerHTML = "<?php echo $albumsHtml; ?>";
        }
    </script>
   
</html>
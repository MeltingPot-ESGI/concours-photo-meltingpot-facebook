<?php
    require "./ressource/lib/facebook-php-sdk-v4-4.0-dev/autoload.php";
    include_once("ressource/include/fonction.php");
    
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
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
    $helper = new FacebookRedirectLoginHelper(REDIRECT_URL_PARTICIPATE);
  
    // BDD
    $dbopts = parse_url(DATA_BASE_URL);
    
    try {
        $pdo = new PDO('pgsql:dbname='.ltrim($dbopts["path"],'/').';host='.$dbopts["host"], $dbopts["user"], $dbopts["pass"]);
    } catch (PDOException $e) {
        var_dump($e->getMessage());
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
            $graphObjectUser = $response->getGraphObject();
       
            if (isset($_POST['fileUpload'])) {
                // Vérifie les valeurs
                $_POST['photoName'] = htmlspecialchars($_POST['photoName']);

                if (empty($_POST['photoName'])) {
                    $formErrors[] = "Vous devez remplir tous les champs du formulaire.";
                }

                if (empty($_POST['form_policy'])) {
                    $formErrors[] = "Vous devez accepter le règlement pour pouvoir participer au concours.";
                }

                if (!empty($_POST['fb-photo-id'])) {
                    $fbPhotoId = $_POST['fb-photo-id'];
                } else {
                    if (!isset($_FILES['photo'])) {
                        $formErrors[] = "Veuillez sélectionner un fichier à envoyer.";
                    } else if ($_FILES['photo']['size'] <= 0) {
                        $formErrors[] = "Veuillez sélectionner un fichier à envoyer.";
                    } else if (empty($_FILES['photo']['tmp_name'])) {
                        $formErrors[] = "Le nom du fichier ne peut être vide.";
                    } else {
                        $allowedTypes = array('image/png', 'image/jpeg', 'image/gif');

                        if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
                            $formErrors[] = "Le fichier envoyé n'est pas au bon format. Veuillez envoyer un fichier de type JPEG, PNG ou GIF.";
                        }
                    }
                }
                
                // Si les valeurs sont valides
                if (count($formErrors) <= 0) {
                    // Upload to a user's profile. The photo will be in the
                    // first album in the profile. You can also upload to
                    // a specific album by using /ALBUM_ID as the path     
                    if (empty($fbPhotoId)) {
                        $response = (new FacebookRequest(
                          $session, 'POST', '/me/photos', array(
                            'source' => new CURLFile($_FILES['photo']['tmp_name'], 'image/png'),
                            'message' => $_POST['photoName']
                          )
                        ))->execute()->getGraphObject();
                    }

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
                        $email = $graphObjectUser->getProperty('email');
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
                    $idUser = $user['id'];
                    $name = $_POST['photoName'];
                    $dateAdd = date('Y-m-d H:i:s');
                    $note = 0;
                    
                    if (empty($fbPhotoId)) {
                        $photoIdFacebook =  $response->getProperty('id');
                    } else {
                        $photoIdFacebook = $fbPhotoId;
                    }
                    
                    $stmtPhotos = $pdo->prepare("SELECT * FROM \"Photos\" WHERE id_facebook = :id_facebook;");
                    $stmtPhotos->execute(
                        array(':id_facebook' => $photoIdFacebook)
                    );

                    // Utilisateur existe dans la BDD
                    $photos = $stmt->fetch(PDO::FETCH_ASSOC);
                    die(var_dump($photos));
                    
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
          $requestPhotoAlbum = new FacebookRequest(
                $session,
                'GET',
                '/'.$album->cover_photo
            );
            
            $pluriel = "";
            
            $responsePhotoAlbum = $requestPhotoAlbum->execute();

            $graphObjectPhotoAlbum = $responsePhotoAlbum->getGraphObject();
            $imagesPhotoAlbum = $graphObjectPhotoAlbum->getProperty('images')->asArray();

            $imagePhotoAlbum = $imagesPhotoAlbum[0];

            $sourcePhotoAlbum = $imagePhotoAlbum->source;
            
            
            if ($album->count > 1) {
                $pluriel = "s";
            }
            
            $albumsHtml .= "<div class='fb-album-block'>"
                    . "<img class='fb-album-photo' src='".$sourcePhotoAlbum."' onclick='clickFbAlbum(".$album->id.");'><br>"
                    . "<div class='fb-album-text-block'>"
                        . "<a onclick='clickFbAlbum(".$album->id.");'>".$album->name."</a><br>"
                        . $album->count." photo".$pluriel
                    . "</div>"
                  . "</div>";
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
    <script type="text/javascript">
        window.fbAsyncInit = function() {
            FB.init({
              appId      : '342576715932172',
              cookie: true,
              xfbml      : true,
              oauth: true,
              version    : "v2.3"
            });
            
    <?php
        if (!$session) {
    ?>
        FB.getLoginStatus(function() {
            FB.login(function(response) {
               if (response.authResponse) {
                   var dataPost = {'accessToken':FB.getAuthResponse()['accessToken']};

                   $.ajax({
                       type: "POST",
                       url: "saveSession.php",
                       data: dataPost,
                       dataType: 'html'
                   }).done(function() {
                       location.reload();
                   })
                   .fail(function() {
                   });
               } else {
                 console.log('User cancelled login or did not fully authorize.');
               }
           });
        }, {scope: 'publish_actions,user_photos'});
    <?php
        }
    ?>
        };
    </script>
   <body>
        <div id="wrapper">
            <div class="under_wrapper">
                <div id="wrapper_admin">
                    <div class="encart_concours">
                        <?php
                            if (!empty($graphObject)) {
                        ?>
                            <div class="fb-profile-picture-block"><img src="http://graph.facebook.com/<?php echo $graphObject->getId(); ?>/picture" class="fb-profile-picture" alt="Facebook profile picture"><h3 class="fb-profile-picture-text"><?php echo $graphObject->getName(); ?></h3></div>
                        <?php
                            }
                        ?>
                        
                        <div class="encart-participe-concours">
                            <h1>PARTICIPER AU CONCOURS</h1>
                            <a href="index.php" class="fb-gallery-link fb-form-participate-infos"><- Revenir à la galerie</a>
                            <?php
                                if (isset($_POST['fileUpload'])) {
                                    if (count($formErrors) > 0) {
                                        echo '<div class="form-erros fb-form-participate-infos">';

                                        foreach ($formErrors as $error) {
                                            echo '<span class="form-error">'.$error.'</span><br>';
                                        }

                                        echo '</div>';
                                    } else {
                                        echo '<div class="form-success fb-form-participate-infos">';

                                        echo '<span class="success-message">'.$successMessage.'</span>';

                                        echo '</div>';
                                    }
                                }
                            ?>

                            <?php
                                if (!empty($graphObject)) {
                            ?>
                            <div class="fb-form-participate-infos">
                                Veuillez sélectionner la photo avec laquelle vous souhaitez participer.
                            </div>
                            <form method='post' action="#" enctype="multipart/form-data" onsubmit="return validateForm();">
                                <input type="hidden" name="fileUpload" value='1' />
                                <table>
                                    <tr>
                                        <td>
                                            <input type="file" name="photo" id="photo" />
                                        </td>
                                        <td>
                                            ou
                                        </td>
                                        <td id="fb-albums" class="fb-albums-block">
                                            <?php echo isset($albumsHtml) ? $albumsHtml : ''; ?>
                                        </td>
                                    </tr>
                                </table>
                                
                                <div class="fb-form-participate-infos" id="participate-end-form">
                                    <div id="participate-end-errors-form" class="form_ligne"></div>
                                    <label>Nom de l'image : </label><input type="text" name="photoName" id="photoName" value="" />
                                    <div class="form_ligne"><label for="form_gooddeals" class="label_checkbox">Je veux recevoir les bons plans </label><input type="checkbox" name="form_gooddeals" value="1" id="form_gooddeals"></div>
                                    <div class="form_ligne"><label for="form_policy" class="label_checkbox">J'accepte <a href="cgu.php">le règlement</a> </label><input type="checkbox" name="form_policy" id="form_policy" value="1" id="form_reglement"></div>
                                </div>
                                
                                <div class="fb-form-participate-submit">
                                    <input type="submit" class="button" name="form_validate" value="Participer">
                                </div>
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
                                    
                                    photosHtml += "<img src='"+photo.source+"' id='"+photo.id+"' onclick='setHighlighted(this);' class='fb-album-photo-inside' alt='Photo facebook'>";
                                }
                                
                                photosHtml += "<input type='hidden' id='fb-photo-id' name='fb-photo-id' value='' />";
                                
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
        
        function setHighlighted(element) {
            var hightliteds = document.getElementsByClassName("highlited");
            var hidden = document.getElementById('fb-photo-id');
            
            for (i=0, l=hightliteds.length; i < l; i++) {
                var hightlited = hightliteds[i];
                
                hightlited.classList.remove("highlited");
            }
            
            element.classList.add("highlited");
            
            hidden.value = element.id;
        }
        
        function clickReturnAlbums() {
            document.getElementById("fb-albums").innerHTML = "<?php echo isset($albumsHtml) ? $albumsHtml : ''; ?>";
        }
    </script>
   
</html>
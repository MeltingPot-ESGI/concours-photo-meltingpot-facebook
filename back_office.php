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

$formErrors = [];

$id = "meltingPot";
$salt = "dfrkhglsdkgbqdr";
$mdp = md5("meltingPot".$salt);

if (isset($_POST['formSend'])) {
    $name = htmlspecialchars($_POST['name']);
    $dateStart = $_POST['dateStart'];
    $dateEnd = $_POST['dateEnd'];
    $isStopped = isset($_POST['isStopped']) ? $_POST['isStopped'] : 0;
    
    if (empty($name)) {
        $formErrors[] = "Nom du concours doit être renseigné.";
    }
    
    if (empty($dateStart)) {
        $formErrors[] = "Date de début doit être renseigné.";
    } else {
        $d = DateTime::createFromFormat('d-m-Y', $dateStart);
        
        if (! ($d && $d->format('d-m-Y') == $dateStart)) {
            $formErrors[] = "Date de début incorrecte.";
        }
    }
    
    if (empty($dateEnd)) {
        $formErrors[] = "Date de fin doit être renseigné.";
    } else {
        $d = DateTime::createFromFormat('d-m-Y', $dateEnd);
        
        if (! ($d && $d->format('d-m-Y') == $dateEnd)) {
            $formErrors[] = "Date de fin incorrecte.";
        }
    }
    
    if (count($formErrors) < 1) {
        $stmt = $pdo->prepare("UPDATE \"Concours\" SET name = :name, date_start = :date_start, date_end= :date_end, is_stopped = :is_stopped WHERE id = 1");
        $res = $stmt->execute(
            array(
                ':name' => $name,
                ':date_start' => $dateStart,
                ':date_end' => $dateEnd,
                ':is_stopped' => $isStopped,
            )
        );
        
        $successMessage = "Les modifications ont été prises en compte.";
    }
} else if (isset($_POST['formAuthenticatedSend'])) {
    if ($_POST['id'] == $id && md5($_POST['password'].$salt) == $mdp) {
        $successMessage = "Vous êtes authentifié.";
        $_SESSION['back_office_authentified'] = true;
    } else {
        $formErrors[] = "Identifiant ou mot de passe incorrect.";
    }
}

$stmt = $pdo->prepare("SELECT * FROM \"Concours\" WHERE id = :id;");

$stmt->execute(
    array(':id' => 1)
);

// Utilisateur existe dans la BDD
$concours = $stmt->fetch(PDO::FETCH_ASSOC);

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
                        <h1>Administration du concours</h1>
                        
                        <?php
                            if (isset($_POST['formSend']) || isset($_POST['formAuthenticatedSend'])) {
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
                            if (!empty($_SESSION['back_office_authentified'])) {
                        ?>
                            <form method='post' action="#" enctype="multipart/form-data" onsubmit="return validateForm();">
                                <input type="hidden" name="formSend" value="1"/>
                                <div class="fb-form-participate-infos" id="back-office-form">
                                    <div class="form_ligne"><label>Nom du concours : </label><input type="text" name="name" id="name" value="<?php echo $concours['name']; ?>" /></div>
                                    <div class="form_ligne"><label>Date de début (jj-mm-aaaa) : </label><input type="text" name="dateStart" id="dateStart" value="<?php echo date('d-m-Y', strtotime($concours['date_start'])); ?>" /></div>
                                    <div class="form_ligne"><label>Date de fin (jj-mm-aaaa) : </label><input type="text" name="dateEnd" id="dateEnd" value="<?php echo date('d-m-Y', strtotime($concours['date_end'])); ?>" /></div>
                                    <div class="form_ligne"><label for="isStopped" class="label_checkbox">Stopper le concours</a> </label><input type="checkbox" name="isStopped" value="1" <?php echo $concours['is_stopped'] ? 'checked' : ''; ?> id="isStopped"></div>
                                </div>
                                <div class="fb-form-participate-submit">
                                    <input type="submit" class="button" name="form_validate" value="Valider">
                                </div>
                            </form>
                        <?php
                            } else {
                        ?>
                            <form method='post' action="#" enctype="multipart/form-data" onsubmit="return validateForm();">
                                <input type="hidden" name="formAuthenticatedSend" value="1"/>
                                <div class="fb-form-participate-infos" id="back-office-form">
                                    <label>Identifiant : </label><input type="text" name="id" id="id" value="" />
                                    <label>Mot de passe : </label><input type="password" name="password" id="password" value="" />
                                </div>
                                <div class="fb-form-participate-submit">
                                    <input type="submit" class="button" name="form_validate" value="Valider">
                                </div>
                            </form>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</htmL>
<?php

require "./ressource/lib/facebook-php-sdk-v4-4.0-dev/autoload.php";

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;
use Facebook\GraphUser;

class FacebookController
{
    const APP_ID = "342576715932172";
    const APP_SECRET = "f3f97bb62cd6c603fe00128e847586dd";
    const REDIRECT_URL = "https://meltingpot-photo-contest.herokuapp.com/formulaire.php";
    const FB_TOKEN = 'fb_token';
    
    public static function generateSession() {
        FacebookSession::setDefaultApplication(APP_ID, APP_SECRET);
    
        $loginUrl = "";
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
    }
}
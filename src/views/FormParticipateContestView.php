<?php
session_start();
class FormParticipateContestView extends View
{
    public function getView($viewParams = array())
    {
        $graphObject = null;
        
        if (isset($_SESSION['fb_graph_object'])) {
            $graphObject = $_SESSION['fb_graph_object'];
        }
        
        $loginUrl = $viewParams['loginUrl'];
        
        $html = '<div class="encart_concours">
                <h1>PARTICIPER AU CONCOURS</h1>';

        
        if (!empty($graphObject)) {
            $text = print_r(get_class_methods($graphObject), true);
            $text .= print_r($graphObject, true);
            return $text;
            /*
            $html .= "Vous êtes connecté en tant que ".$graphObject->getName();
            $html .= ' <img src="http://graph.facebook.com/'.$graphObject->getId().'/picture" alt="Facebook profile picture" height="42" width="42">';*/
        } else {
            $html .= '<a class="fb-button button" href="'.$loginUrl.'">S\'authentifier avec Facebook</a>';
        }

        $html .= '
                <div
                    class="fb-like"
                    data-share="true"
                    data-width="450"
                    data-show-faces="true">
                </div>
        ';
        /*
                    if($session) {
                        try {
                          // Upload to a user's profile. The photo will be in the
                          // first album in the profile. You can also upload to
                          // a specific album by using /ALBUM_ID as the path     
                          $response = (new FacebookRequest(
                            $session, 'POST', '/me/photos', array(
                              'source' => new CURLFile('path/to/file.name', 'image/png'),
                              'message' => 'User provided message'
                            )
                          ))->execute()->getGraphObject();

                          // If you're not using PHP 5.5 or later, change the file reference to:
                          // 'source' => '@/path/to/file.name'

                          echo "Posted with id: " . $response->getProperty('id');

                        } catch(FacebookRequestException $e) {

                          echo "Exception occured, code: " . $e->getCode();
                          echo " with message: " . $e->getMessage();
                        }
                    }*/

         $html .=  '<form>
                        <input type="text" name="name" value="Nom" id="form_name" size="50" onclick="this.value=\'\';"><br>
                        <input type="text" name="email" value="E-mail" id="form_email" size="50" onclick="this.value=\'\';"><br>
                        <input type="text" name="city" value="Ville" id="form_city" size="50" onclick="this.value=\'\';"><br>
                        <div class="form_ligne"><label for="form_gooddeals" class="label_checkbox">Je veux recevoir les bons plans </label><input type="checkbox" name="form_gooddeals" value="" id="form_gooddeals"></div>
                        <div class="form_ligne"><label for="form_policy" class="label_checkbox">J\'accepte <a href="cgu.php">le règlement</a> </label><input type="checkbox" name="form_policy" value="" id="form_reglement"></div>
                        <input type="submit" name="form_validate" value="Participer">
                      </form>
                    </div>';

         return $html;
    }
}
?>
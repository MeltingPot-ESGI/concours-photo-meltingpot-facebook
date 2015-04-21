<?php

class FormParticipateContestView extends View
{
    public function getView($viewParams = array())
    {
        if (isset($_SESSION['fb_graph_object'])) {
            $graphObject = $viewParams['fb_graph_object'];
        }
        
        $loginUrl = $viewParams['loginUrl'];
        
        $html = '<div class="encart_concours">
                <h1>PARTICIPER AU CONCOURS</h1>';


        if (!empty($graphObject)) {
            $html .= "Vous êtes connecté en tant que ".$graphObject->getName();
            $html .= ' <img src="http://graph.facebook.com/'.$graphObject->getId().'/picture" alt="Facebook profile picture" height="42" width="42">';
        } else {
            $html .= '<a class="fb-button" href="'.$loginUrl.'">S\'authentifier avec Facebook</a>';
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
                        <label for="form_name">Nom: </label><input type="text" name="name" value="Nom" id="form_name" maxlength="100" size="50" onclick="this.value=\'\';"><br>
                        <label for="form_email">E-mail: </label><input type="text" name="email" value="E-mail" id="form_email" maxlength="100" size="50" onclick="this.value=\'\';"><br>
                        <label for="form_city">Ville: </label><input type="text" name="city" value="Ville" id="form_city" maxlength="100" size="50" onclick="this.value=\'\';"><br>
                        <label for="form_gooddeals">Je veux recevoir les bons plans </label><input type="checkbox" name="form_gooddeals" value="" id="form_gooddeals"><br>
                        <label for="form_policy">J\'accepte <a href="cgu.php">le règlement</a> </label><input type="checkbox" name="form_policy" value="" id="form_reglement">
                        <input type="submit" name="form_validate" value="Participer">
                </form>
            </div>';

         return $html;
    }
}
?>
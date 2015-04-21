<?php

include(dirname(dirname(__DIR__))."/ressource/include/fonction.php");

/**
 * Gère les requêtes principales
 */
class DefaultController extends Controller
{
    /**
     * Retourne le formulaire de participation au concours
     * @return type
     */
    public function getFormParticipateContest ($params = array())
    {
        return $this->getView("FormParticipateContest", $params);
    }
    
    /**
     * Retourne les CGU
     * @return type
     */
    public function getCgu ($params = array())
    {
        return $this->getView("cgu", $params);
    }
}
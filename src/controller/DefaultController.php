<?php

/**
 * Gère les requêtes principales
 */
class DefaultController extends Controller
{
    /**
     * Retourne le formulaire de participation au concours
     * @return type
     */
    public function getFormParticipateContest ()
    {
        return $this->getView("formParticipateContest");
    }
    
    /**
     * Retourne les CGU
     * @return type
     */
    public function getCgu ()
    {
        return $this->getView("cgu");
    }
}
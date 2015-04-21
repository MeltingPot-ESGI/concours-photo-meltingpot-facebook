<?php
    include_once("../include/fonction.php"); 

    add_slashes($_POST);
    $html = '';
    $params = array();

    if (isset($_POST['params'])) {
        $params = json_decode($_POST['params']);
    }

    /**
     * Pour pouvoir renvoyer la vue, il faut que la fonction du controleur, le nom de la vue et le fichier de la vue est le même nom
     */
    $controller = new DefaultController();
    $function = "get".$_POST['action'];
    $html = $controller->{$function}($params);

    echo $html;
?>
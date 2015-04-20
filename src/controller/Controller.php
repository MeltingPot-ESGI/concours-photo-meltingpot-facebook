<?php

class Controller
{
    const VIEWS_DIRECTORY = "../views/";
    const VIEWS_FILE_EXTENSION = ".php";
    
    public function getView($name)
    {
        $viewFile = self::VIEWS_DIRECTORY.$name.self::VIEWS_FILE_EXTENSION;
        
        if (file_exists($viewFile)) {
            return file_get_contents($name);
        } else {
            return "";
        }
    }
}
<?php

class Controller
{
    const VIEWS_FILE_EXTENSION = ".php";
    
    public function getView($name, $viewParams = array())
    {
        $name .= "View";
        
        $viewFile = dirname(__DIR__)."/views/".$name.self::VIEWS_FILE_EXTENSION;
        
        if (file_exists($viewFile)) {
            include $viewFile;
            
            $viewClass = ucfirst($name);
            $viewClass = new $viewClass();
            
            return $viewClass->getView($viewParams);
        } else {
            return "";
        }
    }
}
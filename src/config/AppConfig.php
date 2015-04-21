<?php

class AppConfig
{
    public static function getViewsDirectory()
    {
        return __DIR__."/views/";
    }
    
    public static function getControllersDirectory()
    {
        return __DIR__."/controller/";
    }
    
    public static function getModelsDirectory()
    {
        return __DIR__."/model/";
    }
    
    public static function getRessourcesDirectory()
    {
        return dirname(dirname(dirname(__FILE__)))."/ressource/";
    }
    
    public static function getCssDirectory()
    {
        return "http://".$_SERVER['SERVER_NAME']."/ressource/public/_css/";
    }
    
    public static function getJsDirectory()
    {
        return "http://".$_SERVER['SERVER_NAME']."/ressource/public/_js/";
    }
}


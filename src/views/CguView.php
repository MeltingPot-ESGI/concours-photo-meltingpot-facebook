<?php

class CguView extends View
{
    public function getView($viewParams)
    {
        return file_get_contents("cgu.txt");
    }
}

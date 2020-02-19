<?php

class Link{
    public $linkedTbl; 
    public $link; 
    public $linksTo;

    public function __construct($_linkedTbl, $_link, $_linksTo){
        $this->linkedTbl = $_linkedTbl;
        $this->link = $_link;
        $this->linksTo = $_linksTo;
    }
}


?>
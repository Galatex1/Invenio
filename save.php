<?php

require_once "links.php";

foreach ($_POST as $key => $value) {
    if($value=="")
        die("Error - Vyplnte prosím všechny položky");
    
    if(!in_array($key, $table->columns) && $key != "Uložit")
        die("Error - Něco se pokazilo!");
}



$table->add($_POST);


?>
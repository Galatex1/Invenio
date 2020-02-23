<?php

require_once "../links.php";



foreach ($_POST as $key => $value) {

    //print_r($_POST);

    if($value=="")
        die("Error - Vyplnte prosím všechny položky");
    
    if(!in_array($key, $table->columns) && $key != "Uložit" && $key != "edited" && !contains($key, "-old"))
        die("Error - Něco se pokazilo!");
}


if(isset($_POST['edited']))
    $table->edit($_POST);
else
    $table->add($_POST);


?>
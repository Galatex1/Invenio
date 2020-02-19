<?php  
require_once "links.php";
?>


<form action="">

<?
    foreach ($table->columns as $key) {

                

        $isLinked = $table->isLinked($key);

        if($isLinked === false)
        {
            echo "<option value=\"$table->tblName.$key\">";
            echo $key;
            echo "</option>";
        }
        else {


            foreach ($table->links[$isLinked]->linkedTbl->columns as $cl) {
                echo "<option value=\"$isLinked.$cl\">";
                echo $isLinked.".".$cl;
                echo "</option>";
            }

            
        }
    }
?>


</form>
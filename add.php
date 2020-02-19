<?php  
require_once "links.php";
?>


<form action="">

<?
    foreach ($zak->columns as $key) {

                

        $isLinked = $zak->isLinked($key);

        if($isLinked === false)
        {
            echo "<option value=\"$zak->tblName.$key\">";
            echo $key;
            echo "</option>";
        }
        else {


            foreach ($zak->links[$isLinked]->linkedTbl->columns as $cl) {
                echo "<option value=\"$isLinked.$cl\">";
                echo $isLinked.".".$cl;
                echo "</option>";
            }

            
        }
    }
?>


</form>
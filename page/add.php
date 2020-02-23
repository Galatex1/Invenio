<?php  
require_once "../links.php";
require_once "../utils/year.php";
?>


<form class="addEdit" action="../action/save.php" target="_blank">
    <h2>
    <?php 
        echo "Nový Záznam -  $table->name"; 
    ?>
    </h2>

<?php
    foreach ($table->columns as $key) {

        $isLinked = $table->isLinked($key);
        $type = mysqli_fetch_assoc($table->getColumnType($key))['DATA_TYPE'];
        //print_r($type);

        if($isLinked === false )
        {

            echo "<span>";
           
            if($table->isPrimary($key))
            {
                echo "<img class=\"autoInc\" src=\"assets/img/key2.png\" title=\"Primární klíč\"/>";
            }
            else {
                echo "<img class=\"autoInc\"  />";
            }

            echo "$key: <input type=\"";
            
            if($key == "email")
                echo "email";
            else if($key == "telefon")
                echo "tel";
            else if($type == "int")
            {
                if($table->isAutoInc($key))
                    echo "text\" disabled value=\"Auto Increment\"";
                else 
                    echo "number";
               
            }
            else if($type == "text")
                echo "text";
            else if($type == "year")
                echo "hidden\" disabled min=\"1000\" max=\"9999\"";
            else if($type == "datetime")
                echo"text\" placeholder=\"1900-12-31 24:00:00";
            else 
                echo "text";
            
            echo "\" name=\"$key\">";
            
            if($type == "year")
                selectYear($key);
            
            echo "</span>";

        }
        else {

            $lk = $table->links[$isLinked];
            $lkTable = $lk->linkedTbl;

            $res = $lkTable->getAllLinked(["*"]);
            echo "<span>";

            if($table->isPrimary($key))
            {
                echo "<img class=\"autoInc\" src=\"assets/img/key2.png\" title=\"Primární klíč\"/>";
            }
            else {
                echo "<img class=\"autoInc\"  />";
            }

            echo "$key: ";
            
            echo "<select name=\"$key\">";
            foreach ($res as $option) {
                
                echo "<option value=\"".$option["$isLinked.$lk->linksTo"]."\">";
                //echo $option["id"]." - ";
                foreach ($lkTable->columns as $c => $value) {
                    //echo $value;
                    echo $option["$isLinked.$value"].($value == $table->links[$isLinked]->linksTo ?  " - " : " ");
                }
                echo "</option>";
            }
            echo "</select></span>";

            
        }
    }

?>

<span><br><input class="btn btnReset" type="reset" value="Reset"/><input class="btn btnSave" type="submit" value="Uložit"/></span>
</form>
<?php  
require_once "links.php";
require_once "year.php";
?>


<form class="addEdit" action="save.php" target="_blank">
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
            if($key != "id")
            {
                echo "<span>$key: <input type=\"";
                
                if($key == "email")
                    echo "email";
                else if($key == "telefon")
                    echo "tel";
                else if($type == "int")
                    echo "number";
                else if($type == "text")
                    echo "text";
                else if($type == "year")
                    echo "hidden\" disabled min=\"1000\" max=\"9999\"";
                else 
                    echo "text";
                
                echo "\" name=\"$key\">";
                
                if($type == "year")
                    selectYear($key);
                
                echo "</span>";



            }

        }
        else {

            $lkTable = $table->links[$isLinked]->linkedTbl;

            $res = $lkTable->getAllLinked(["*"]);
            echo "<span>$key: ";
            echo "<select name=\"$key\">";
            foreach ($res as $option) {
                echo "<option value=\"".$option["id"]."\">";
                // echo $option["id"]." - ";
                foreach ($lkTable->showColumns as $c => $value) {
                    echo $option[$value].($value == "id" ?  " - " : " ");
                }
                echo "</option>";
            }
            echo "</select></span>";

            
        }
    }

?>

<span><input class="btn btnReset" type="reset" value="Reset"/><input class="btn btnSave" type="submit" value="Uložit"/></span>
</form>

<div class="response"></div>
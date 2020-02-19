<?php  
require_once "links.php";

$i = $_POST["filters"];

?>
<div class="fil">
    <select name="column-<?php echo $i ?>" id="column-<?php echo $i ?>">
    <?php
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
    </select>
    <select name="operand-<?php echo $i ?>" id="operand-<?php echo $i ?>">
        <option value=">">></option>
        <option value=">=">>=</option>
        <option value="<"><</option>
        <option value="<="><=</option>
        <option value="=">=</option>
        <option value="<>">!=</option>
        <option value="LIKE">LIKE</option>
    </select>
    <input type="text" name="value-<?php echo $i ?>" id="value-<?php echo $i ?>">
</div>

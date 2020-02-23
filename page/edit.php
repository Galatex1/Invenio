<?php  
require_once "../links.php";
require_once "../utils/year.php";

$filter = "";


//print_r($_POST);
foreach ($_POST['edit'] as $key => $value) {
    $filter .= "$key = '$value' AND ";
}
$filter .= "1 ";

?>


<form class="addEdit" action="../action/save.php" target="_blank">
    <h2>
    <?php 
        echo "Editovat Záznam -  $table->name"; 
    ?>
    </h2>

<?php



    $result = $table->getAllLinked(["*"], $filter, 0, 1);

    while($data = mysqli_fetch_assoc($result))
    {


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
                        echo "number";       
                }
                else if($type == "text")
                    echo "text";
                else if($type == "datetime")
                    echo"text\" placeholder=\"1900-12-31 24:00:00";
                else if($type == "year")
                    echo "hidden\" disabled min=\"1000\" max=\"9999\"";
                else 
                    echo "text";

                    //echo $type;
                
                echo "\" name=\"$key\" value=\"".htmlspecialchars($data["$table->tblName.$key"])."\"  >";
                
                if($type == "year")
                    selectYear($key, 1901, 2050, $data["$table->tblName.$key"]);
                
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
                
                    echo "<option value=\"".$option["$isLinked.$lk->linksTo"]."\"";
                    
                    if($option["$isLinked.$lk->linksTo"] == $data["$table->tblName.$key"])
                        echo " selected ";
                    echo ">";
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

        foreach ($table->columns as $key) {

            $isLinked = $table->isLinked($key);
            $type = mysqli_fetch_assoc($table->getColumnType($key))['DATA_TYPE'];
            //print_r($type);

            if($isLinked === false )
            {
                echo "<input type=\"hidden\" name=\"$key-old\" value=\"".htmlspecialchars($data["$table->tblName.$key"])."\"  />";                              
            }
            else {

                $lk = $table->links[$isLinked];
                $lkTable = $lk->linkedTbl;

                $res = $lkTable->getAllLinked(["*"]);

                foreach ($res as $option) {
                    if($option["$isLinked.$lk->linksTo"] == $data["$table->tblName.$key"])             
                        echo "<input name=\"$key-old\" type=\"hidden\" value=\"".htmlspecialchars($option["$isLinked.$lk->linksTo"])."\"/>";                              
                }
      
            }
        }



    }

?>
<input type="hidden" name="edited" value="true"/>
<span><br><input class="btn btnReset" type="reset" value="Reset"/><input class="btn btnSave" type="submit" value="Uložit"/></span>
</form>
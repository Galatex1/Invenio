<?php  
require_once "links.php";
?>


<form class="addEdit" action="">
    <h2>Novy zaznam - <?php echo $table->tblName; ?></h2>

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
                
                if($type == "int")
                    echo "number";
                else if($type == "text")
                    echo "text";
                else if($type == "year")
                     echo "number\" min=\"1000\" max=\"9999\"";
                else 
                    echo "text";
                
                echo "\" name=\"$table->tblName.$key\"></span>";
            }

        }
        else {

            $lkTable = $table->links[$isLinked]->linkedTbl;

            $res = $lkTable->getAllLinked(["*"]);
            echo "<span>$key: ";
            echo "<select name=\"$isLinked\">";
            foreach ($res as $option) {
                echo "<option name=\"".$option["id"]."\">";
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


</form>
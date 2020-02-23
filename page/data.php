<?php
require_once "../links.php";

//error_reporting(0);

if(session_status() !== PHP_SESSION_ACTIVE)
session_start();

$cols = array();

function makeFilters($conn)
{
    $filter = "";
    $index = 0;
    $st = true;
    while(isset($_POST["column-$index"]))
    {
        if( $_POST["column-$index"] && $_POST["operand-$index"] && strlen($_POST["value-$index"]) > 0 )
        {
            if(!$st){
                $filter .= " AND ";
            }

            
                $filter .= mysqli_real_escape_string($conn, $_POST["column-$index"])." ".mysqli_real_escape_string($conn, $_POST["operand-$index"])." ". (is_numeric($_POST["value-$index"]) ? mysqli_real_escape_string($conn, $_POST["value-$index"]) : "'".mysqli_real_escape_string($conn, $_POST["value-$index"])."'")   ." ";


            $st = false;
        }

        $index++;
    }

    $filter = $filter == "" ? "1 " : $filter;

    if(isset($_POST["o_r_d_e_rBy"]) && isset($_POST["o_r_d_e_rDir"]) && $_POST["o_r_d_e_rBy"] != "" && $_POST["o_r_d_e_rDir"] != "")
        $filter .= "ORDER BY ".mysqli_real_escape_string($conn, $_POST["o_r_d_e_rBy"])." ". mysqli_real_escape_string($conn, $_POST["o_r_d_e_rDir"])." ";

    return $filter;
}


// if(isset($_POST['delete']) && $_POST['delete'] !="")
// {
//     $id = $_POST['delete'];
//     $table->delete($id);

// }


foreach ($_POST as $key => $value) {

    if($key != "odeslat" && $key != "delete" && strpos($key, "join-") === false && strpos($key, "column-") === false && strpos($key, "operand-") === false && strpos($key, "value-") === false && strpos($key, "o_r_d_e_r") === false && strpos($key, "l_i_m_i_t") === false)
    {


        $cols[] = $key;
    }

}

//  echo makeFilters();
$res = $table->getAllLinked($cols,  makeFilters($conn), 0,  $_POST["l_i_m_i_t"] );

$first = true;

$all = [];
$linkedCols = [];




echo "<table>";

echo "<tr>";
    echo "<th style=\"white-space:nowrap;\">Akce</th>";
    foreach ($cols as $key) {
        if(!contains($key, "::"))
        {
            $k = str_replace(":", ".", $key);
            $order = (isset($_POST["o_r_d_e_rDir"]) && isset($_POST["o_r_d_e_rBy"]) && $k == $_POST["o_r_d_e_rBy"]) ? $_POST["o_r_d_e_rDir"] : "";

            echo "<th class=\"column ".$order."\" style=\"white-space:nowrap;\">".$k."</th>";
        }
        else {
            $cl = str_replace("::", ".", $key);
            $linkedCols[$cl] = $cl;
        }
    }

    foreach ($table->primary as $primary){       
        if(!inArray("$table->tblName:$primary", $cols) && !inArray("$table->tblName::$primary", $cols))
        {
            $all[] = $primary;
        }
    }

echo "</tr>";


while($row = mysqli_fetch_assoc($res))
{

    //  if($first)
    //  {
        
        

    //  $first = false;
    //  }
    //print_r($row);
    
    echo "<tr>";
    echo "<td style=\"white-space:nowrap;\">"; 

    if(count($all) == 0)
    {
        echo "<button class=\"btn btnDel\" title=\"Smazat zaznam\" value='";
        //$row["$table->tblName.id"]
        
        echo "[";
        foreach ($table->primary as $primary) {
            
                echo "[\"$table->tblName.$primary\", \"".$row["$table->tblName.$primary"]."\"],";
        }

        echo "\" \"";

        echo "]'><img src=\"assets/img/delete.png\" height=\"15px\" width=\"15px\"/></button>";
        echo " <button class=\"btn btnEdit\" title=\"Upravit zaznam\" value='";
        echo "[";
        foreach ($table->primary as $primary) {
            
                echo "[\"$table->tblName.$primary\", \"".$row["$table->tblName.$primary"]."\"],";
        }
        echo "\" \"";
        echo "]'><img src=\"assets/img/change.png\" height=\"15px\" width=\"15px\"/></button>";
    }
    // else if(isset($row["$table->tblName.ID"]))
    // {
    //     echo "<button class=\"btn btnDel\" title=\"Smazat zaznam\" value=\"".$row["$table->tblName.ID"]."\"><img src=\"assets/img/delete.png\" height=\"15px\" width=\"15px\"/></button> <button class=\"btn btnEdit\" title=\"Upravit zaznam\" value=\"".$row["$table->tblName.ID"]."\"><img src=\"assets/img/change.png\" height=\"15px\" width=\"15px\"/></button>";
    // }
    else {
        echo "<center title=\"Chcete li upravovat nebo mazat, vyberte i policka: ".implode(", ", $all).".\"><span>?</span></center>";
    }
    echo "</td>";

    foreach ($row as $key => $value) {
        if(!isset($linkedCols[$key]))
            echo "<td >".htmlspecialchars($value)."</td>";

    }
    echo "</tr>";
    

}

echo "</table>";

?>
<?php
require_once "links.php";

if(session_status() !== PHP_SESSION_ACTIVE)
session_start();

$cols = array();

function makeFilters()
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

            
                $filter .= $_POST["column-$index"]." ".$_POST["operand-$index"]." ". (is_numeric($_POST["value-$index"]) ? $_POST["value-$index"] : "'".$_POST["value-$index"]."'")   ." ";


            $st = false;
        }

        $index++;
    }

    $filter = $filter == "" ? "1 " : $filter;

    if(isset($_POST["orderBy"]) && isset($_POST["orderDir"]))
        $filter .= "ORDER BY ".$_POST["orderBy"]." ". $_POST["orderDir"]." ";

    return $filter;
}


if(isset($_POST['delete']) && $_POST['delete'] !="")
{
    $id = $_POST['delete'];
    $table->delete($id);

}


foreach ($_POST as $key => $value) {

    if($key != "odeslat" && $key != "delete" && strpos($key, "join-") === false && strpos($key, "column-") === false && strpos($key, "operand-") === false && strpos($key, "value-") === false && strpos($key, "order") === false)
    {


        $cols[] = $key;
    }

    else if(strpos($key, "column-") || strpos($key, "operand-") || strpos($key, "value-") )
    {

    }

}

//  echo makeFilters();
$res = $table->getAllLinked($cols,  makeFilters());

$first = true;

echo "<table>";

while($row = mysqli_fetch_assoc($res))
{

    if($first)
    {
        

        echo "<tr>";
        echo "<th style=\"white-space:nowrap;\">Akce</th>";
        foreach ($row as $key => $value) {
            $order = (isset($_POST["orderDir"]) && isset($_POST["orderBy"]) && $key == $_POST["orderBy"]) ? $_POST["orderDir"] : "";
            echo "<th class=\"column ".$order."\" style=\"white-space:nowrap;\">$key</th>";
        }
        echo "</tr>";
    $first = false;
    }

    
    echo "<tr>";
        echo "<td style=\"white-space:nowrap;\">";
        if(isset($row["$table->tblName.id"]))
        {
            echo "<button class=\"btn btnDel\" title=\"Smazat zaznam\" value=\"".$row["$table->tblName.id"]."\"><img src=\"delete.png\" height=\"15px\" width=\"15px\"/></button> <button class=\"btn btnEdit\" title=\"Upravit zaznam\" value=\"".$row["$table->tblName.id"]."\"><img src=\"change.png\" height=\"15px\" width=\"15px\"/></button>";
        }
        else {
            echo "<center title=\"Chcete li upravovat nebo mazat, vyberte i policko ID.\"><span>?</span></center>";
        }
        echo "</td>";
    foreach ($row as $key => $value) {
        echo "<td style=\"white-space:nowrap;\">$value</td>";
    }
    echo "</tr>";
    

}

echo "</table>";

?>
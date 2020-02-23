
<?php
function selectYear($name, $start=1901, $end = 2050, $selected = 1901)
{
    echo "<select name=\"$name\">";

    for ($i=$start; $i <= $end; $i++) { 
        echo "<option value=\"$i\" ";
        echo $i == $selected ? "selected" : "";
        echo ">$i</option>";
    }

    echo "</select>";
}


?>
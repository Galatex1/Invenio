
<?php
function selectYear($name, $start=1901, $end = 2050)
{
    echo "<select name=\"$name\">";

    for ($i=$start; $i <= $end; $i++) { 
        echo "<option value=\"$i\">$i</option>";
    }

    echo "</select>";
}


?>